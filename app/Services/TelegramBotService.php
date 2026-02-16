<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    protected ?string $token;
    protected ?string $adminChatId;
    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->token = config('services.telegram.bot_token');
        $this->adminChatId = config('services.telegram.admin_chat_id');
        $this->accountingService = $accountingService;
    }

    // ─────────────────────────────────────────────
    //  FORMATTING HELPERS
    // ─────────────────────────────────────────────

    /**
     * Create a header card with title
     */
    protected function formatHeader(string $emoji, string $title): string
    {
        return "━━━━━━━━━━━━━━━━━━━━\n"
             . "  {$emoji}  <b>{$title}</b>\n"
             . "━━━━━━━━━━━━━━━━━━━━";
    }

    /**
     * Create a boxed header
     */
    protected function formatBox(string $emoji, string $title, string $subtitle = ''): string
    {
        $box = "┌─────────────────────┐\n"
             . "│  {$emoji}  <b>{$title}</b>\n";

        if ($subtitle) {
            $box .= "│  {$subtitle}\n";
        }

        $box .= "└─────────────────────┘";

        return $box;
    }

    /**
     * Create a labeled info line
     */
    protected function formatField(string $emoji, string $label, string $value): string
    {
        return "{$emoji} {$label}  :  <b>{$value}</b>";
    }

    /**
     * Create a separator line
     */
    protected function separator(): string
    {
        return "━━━━━━━━━━━━━━━━━━━━";
    }

    /**
     * Create a light separator
     */
    protected function lightSeparator(): string
    {
        return "┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄";
    }

    /**
     * Format currency
     */
    protected function formatRupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Escape special HTML characters for Telegram HTML parse mode
     */
    protected function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    // ─────────────────────────────────────────────
    //  WEBHOOK PROCESSING
    // ─────────────────────────────────────────────

    /**
     * Process incoming webhook message
     */
    public function processWebhook(array $update): void
    {
        // Handle callback query (button press)
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
            return;
        }

        $message = $update['message'] ?? null;

        if (!$message) {
            return;
        }

        $chatId = (string) ($message['chat']['id'] ?? '');
        $text = $message['text'] ?? '';

        // Security: Only process messages from admin
        if ($chatId !== $this->adminChatId) {
            $msg = $this->formatHeader('⛔', 'AKSES DITOLAK') . "\n\n"
                 . "Anda tidak memiliki akses\n"
                 . "ke bot ini.";
            $this->sendMessage($chatId, $msg);
            Log::warning('Telegram: Unauthorized access attempt', ['chat_id' => $chatId]);
            return;
        }

        // Route commands
        if (str_starts_with($text, '/biaya')) {
            $this->handleBiayaCommand($chatId, $text);
        } elseif (str_starts_with($text, '/beli')) {
            $this->handleBeliCommand($chatId, $text);
        } elseif (str_starts_with($text, '/tambah')) {
            $this->handleTambahCommand($chatId, $text);
        } elseif (str_starts_with($text, '/kategori')) {
            $this->handleKategoriCommand($chatId);
        } elseif (str_starts_with($text, '/ringkasan')) {
            $this->handleRingkasanCommand($chatId);
        } elseif (str_starts_with($text, '/start') || str_starts_with($text, '/help') || str_starts_with($text, '/menu')) {
            $this->handleHelpCommand($chatId);
        } else {
            $this->handleHelpCommand($chatId);
        }
    }

    /**
     * Handle callback query from inline button press
     */
    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $chatId = (string) ($callbackQuery['message']['chat']['id'] ?? '');
        $data = $callbackQuery['data'] ?? '';
        $callbackId = $callbackQuery['id'] ?? '';

        // Security check
        if ($chatId !== $this->adminChatId) {
            return;
        }

        // Answer callback to remove loading state
        $this->answerCallbackQuery($callbackId);

        // Route based on callback data
        switch ($data) {
            case 'menu_biaya':
                $this->showBiayaMenu($chatId);
                break;
            case 'menu_beli':
                $this->showBeliMenu($chatId);
                break;
            case 'menu_tambah':
                $this->showTambahMenu($chatId);
                break;
            case 'menu_kategori':
                $this->handleKategoriCommand($chatId);
                break;
            case 'menu_ringkasan':
                $this->handleRingkasanCommand($chatId);
                break;
            case 'menu_utama':
                $this->handleHelpCommand($chatId);
                break;
            default:
                // Handle dynamic category selection
                if (str_starts_with($data, 'biaya_')) {
                    $this->promptBiayaInput($chatId, str_replace('biaya_', '', $data));
                } elseif (str_starts_with($data, 'beli_')) {
                    $this->promptBeliInput($chatId, str_replace('beli_', '', $data));
                } elseif (str_starts_with($data, 'tambah_')) {
                    $this->promptTambahInput($chatId, str_replace('tambah_', '', $data));
                }
        }
    }

    // ─────────────────────────────────────────────
    //  COMMAND HANDLERS
    // ─────────────────────────────────────────────

    /**
     * Handle /biaya command - Input operational expense
     * Format: /biaya [kategori] [jumlah] [deskripsi]
     */
    protected function handleBiayaCommand(string $chatId, string $text): void
    {
        $parts = explode(' ', $text, 4);

        if (count($parts) < 3) {
            $msg = $this->formatHeader('❌', 'FORMAT SALAH') . "\n\n"
                 . "📋 <b>Cara Penggunaan:</b>\n"
                 . "<code>/biaya [kategori] [jumlah] [keterangan]</code>\n\n"
                 . "💡 <b>Contoh:</b>\n"
                 . "<code>/biaya gaji 500000 Gaji bulan Feb</code>\n\n"
                 . $this->lightSeparator() . "\n"
                 . "💬 Gunakan /kategori untuk melihat daftar";
            $this->sendMessage($chatId, $msg);
            return;
        }

        $kategori = strtolower($parts[1]);
        $jumlah = floatval(preg_replace('/[^0-9.]/', '', $parts[2]));
        $deskripsi = $parts[3] ?? ucfirst($kategori);

        if ($jumlah <= 0) {
            $msg = $this->formatHeader('❌', 'INPUT TIDAK VALID') . "\n\n"
                 . "Jumlah harus lebih dari <b>0</b>!";
            $this->sendMessage($chatId, $msg);
            return;
        }

        // Find COA subcategory under "Beban Operasional"
        $account = ChartOfAccount::where('type', 'Expense')
            ->where('name', 'like', '%' . $kategori . '%')
            ->whereHas('parent', function ($q) {
                $q->where('name', 'like', '%Operasional%');
            })
            ->first();

        if (!$account) {
            $escapedKategori = $this->escapeHtml($kategori);
            $msg = $this->formatHeader('🔍', 'KATEGORI TIDAK DITEMUKAN') . "\n\n"
                 . "Kategori <b>\"{$escapedKategori}\"</b> tidak ditemukan\n"
                 . "di Beban Operasional.\n\n"
                 . $this->lightSeparator() . "\n"
                 . "💡 Ketik /kategori untuk melihat daftar\n"
                 . "➕ Atau /tambah untuk buat kategori baru";
            $this->sendMessage($chatId, $msg);
            return;
        }

        try {
            $expense = Expense::create([
                'description' => $deskripsi,
                'amount' => $jumlah,
                'date' => now()->toDateString(),
                'chart_of_account_id' => $account->id,
                'user_id' => 1,
            ]);

            $this->accountingService->recordExpense($expense);

            $escapedDesc = $this->escapeHtml($deskripsi);
            $escapedName = $this->escapeHtml($account->name);
            $msg = $this->formatHeader('✅', 'BIAYA TERCATAT') . "\n\n"
                 . $this->formatField('📁', 'Kategori', $escapedName) . "\n"
                 . $this->formatField('💰', 'Jumlah', $this->formatRupiah($jumlah)) . "\n"
                 . $this->formatField('📝', 'Catatan', $escapedDesc) . "\n"
                 . $this->formatField('📅', 'Tanggal', now()->translatedFormat('d M Y')) . "\n\n"
                 . $this->separator();

            $this->sendMessage($chatId, $msg);
            Log::info('Telegram: Expense recorded', ['expense_id' => $expense->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to record expense', ['error' => $e->getMessage()]);
            $msg = $this->formatHeader('💥', 'GAGAL MENYIMPAN') . "\n\n"
                 . "Terjadi kesalahan saat menyimpan biaya.\n\n"
                 . "🔧 <i>" . $this->escapeHtml($e->getMessage()) . "</i>";
            $this->sendMessage($chatId, $msg);
        }
    }

    /**
     * Handle /beli command - Input purchase
     * Format: /beli [kategori] [jumlah] [deskripsi]
     */
    protected function handleBeliCommand(string $chatId, string $text): void
    {
        $parts = explode(' ', $text, 4);

        if (count($parts) < 3) {
            $msg = $this->formatHeader('❌', 'FORMAT SALAH') . "\n\n"
                 . "📋 <b>Cara Penggunaan:</b>\n"
                 . "<code>/beli [kategori] [jumlah] [keterangan]</code>\n\n"
                 . "💡 <b>Contoh:</b>\n"
                 . "<code>/beli ikan 300000 Ikan tenggiri 10kg</code>\n\n"
                 . $this->lightSeparator() . "\n"
                 . "💬 Gunakan /kategori untuk melihat daftar";
            $this->sendMessage($chatId, $msg);
            return;
        }

        $kategori = strtolower($parts[1]);
        $jumlah = floatval(preg_replace('/[^0-9.]/', '', $parts[2]));
        $deskripsi = $parts[3] ?? ucfirst($kategori);

        if ($jumlah <= 0) {
            $msg = $this->formatHeader('❌', 'INPUT TIDAK VALID') . "\n\n"
                 . "Jumlah harus lebih dari <b>0</b>!";
            $this->sendMessage($chatId, $msg);
            return;
        }

        // Find COA subcategory under "Beban Bahan Baku"
        $account = ChartOfAccount::where('type', 'Expense')
            ->where('name', 'like', '%' . $kategori . '%')
            ->whereHas('parent', function ($q) {
                $q->where('name', 'like', '%Bahan Baku%');
            })
            ->first();

        if (!$account) {
            $escapedKategori = $this->escapeHtml($kategori);
            $msg = $this->formatHeader('🔍', 'KATEGORI TIDAK DITEMUKAN') . "\n\n"
                 . "Kategori <b>\"{$escapedKategori}\"</b> tidak ditemukan\n"
                 . "di Beban Bahan Baku.\n\n"
                 . $this->lightSeparator() . "\n"
                 . "💡 Ketik /kategori untuk melihat daftar\n"
                 . "➕ Atau /tambah untuk buat kategori baru";
            $this->sendMessage($chatId, $msg);
            return;
        }

        try {
            $purchase = Purchase::create([
                'purchase_date' => now()->toDateString(),
                'supplier_name' => 'Via Telegram',
                'invoice_number' => 'TG-' . now()->format('YmdHis'),
                'chart_of_account_id' => $account->id,
                'status' => 'paid',
                'notes' => $deskripsi,
                'total_amount' => $jumlah,
            ]);

            $purchase->purchaseDetails()->create([
                'item_name' => $deskripsi,
                'quantity' => 1,
                'unit' => 'pcs',
                'price_per_unit' => $jumlah,
                'subtotal' => $jumlah,
            ]);

            $this->accountingService->recordPurchase($purchase);

            $escapedDesc = $this->escapeHtml($deskripsi);
            $escapedName = $this->escapeHtml($account->name);
            $msg = $this->formatHeader('✅', 'PEMBELIAN TERCATAT') . "\n\n"
                 . $this->formatField('📁', 'Kategori', $escapedName) . "\n"
                 . $this->formatField('💰', 'Jumlah', $this->formatRupiah($jumlah)) . "\n"
                 . $this->formatField('📝', 'Catatan', $escapedDesc) . "\n"
                 . $this->formatField('📅', 'Tanggal', now()->translatedFormat('d M Y')) . "\n"
                 . $this->formatField('🧾', 'Invoice', $purchase->invoice_number) . "\n\n"
                 . $this->separator();

            $this->sendMessage($chatId, $msg);
            Log::info('Telegram: Purchase recorded', ['purchase_id' => $purchase->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to record purchase', ['error' => $e->getMessage()]);
            $msg = $this->formatHeader('💥', 'GAGAL MENYIMPAN') . "\n\n"
                 . "Terjadi kesalahan saat menyimpan pembelian.\n\n"
                 . "🔧 <i>" . $this->escapeHtml($e->getMessage()) . "</i>";
            $this->sendMessage($chatId, $msg);
        }
    }

    /**
     * Handle /tambah command - Create new COA subcategory
     * Format: /tambah [parent] [nama]
     */
    protected function handleTambahCommand(string $chatId, string $text): void
    {
        $parts = explode(' ', $text, 3);

        if (count($parts) < 3) {
            $msg = $this->formatHeader('❌', 'FORMAT SALAH') . "\n\n"
                 . "📋 <b>Cara Penggunaan:</b>\n"
                 . "<code>/tambah [jenis] [nama kategori]</code>\n\n"
                 . "💡 <b>Contoh:</b>\n"
                 . "<code>/tambah operasional Biaya Internet</code>\n"
                 . "<code>/tambah bahan Pembelian Minyak</code>\n\n"
                 . $this->lightSeparator() . "\n"
                 . "🏢 <b>operasional</b> → Beban Operasional\n"
                 . "🥩 <b>bahan</b> → Beban Bahan Baku";
            $this->sendMessage($chatId, $msg);
            return;
        }

        $parentKeyword = strtolower($parts[1]);
        $namaSubkategori = $parts[2];

        $parent = ChartOfAccount::where('type', 'Expense')
            ->whereNull('parent_id')
            ->where(function ($q) use ($parentKeyword) {
                $q->where('name', 'like', '%' . $parentKeyword . '%');
            })
            ->first();

        if (!$parent) {
            $msg = $this->formatHeader('🔍', 'PARENT TIDAK DITEMUKAN') . "\n\n"
                 . "Parent <b>\"" . $this->escapeHtml($parentKeyword) . "\"</b>\ntidak ditemukan.\n\n"
                 . $this->lightSeparator() . "\n"
                 . "📌 <b>Gunakan salah satu:</b>\n"
                 . "  🏢 <code>operasional</code> → Beban Operasional\n"
                 . "  🥩 <code>bahan</code> → Beban Bahan Baku";
            $this->sendMessage($chatId, $msg);
            return;
        }

        $lastChild = ChartOfAccount::where('parent_id', $parent->id)
            ->orderBy('code', 'desc')
            ->first();

        $nextCode = $lastChild ? (int)$lastChild->code + 1 : (int)$parent->code + 1;

        try {
            $newAccount = ChartOfAccount::create([
                'code' => (string)$nextCode,
                'name' => $namaSubkategori,
                'type' => 'Expense',
                'normal_balance' => 'Debit',
                'parent_id' => $parent->id,
                'description' => 'Dibuat via Telegram Bot',
            ]);

            $escapedNama = $this->escapeHtml($namaSubkategori);
            $escapedParent = $this->escapeHtml($parent->name);
            $msg = $this->formatHeader('✅', 'KATEGORI BARU DIBUAT') . "\n\n"
                 . $this->formatField('🔢', 'Kode', $newAccount->code) . "\n"
                 . $this->formatField('📁', 'Nama', $escapedNama) . "\n"
                 . $this->formatField('📂', 'Parent', $escapedParent) . "\n\n"
                 . $this->lightSeparator() . "\n"
                 . "💡 Kategori siap digunakan untuk pencatatan!";

            $this->sendMessage($chatId, $msg);
            Log::info('Telegram: New COA created', ['account_id' => $newAccount->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to create COA', ['error' => $e->getMessage()]);
            $msg = $this->formatHeader('💥', 'GAGAL MEMBUAT KATEGORI') . "\n\n"
                 . "Terjadi kesalahan saat membuat kategori.\n\n"
                 . "🔧 <i>" . $this->escapeHtml($e->getMessage()) . "</i>";
            $this->sendMessage($chatId, $msg);
        }
    }

    /**
     * Handle /kategori command - List available categories
     */
    protected function handleKategoriCommand(string $chatId): void
    {
        $operasional = ChartOfAccount::where('type', 'Expense')
            ->whereHas('parent', function ($q) {
                $q->where('name', 'like', '%Operasional%');
            })
            ->orderBy('code')
            ->get();

        $bahanBaku = ChartOfAccount::where('type', 'Expense')
            ->whereHas('parent', function ($q) {
                $q->where('name', 'like', '%Bahan Baku%');
            })
            ->orderBy('code')
            ->get();

        $msg = $this->formatBox('📂', 'DAFTAR KATEGORI') . "\n\n";

        // Beban Operasional
        $msg .= "🏢 <b>Beban Operasional</b>\n"
              . "    <i>Gunakan dengan /biaya</i>\n"
              . $this->lightSeparator() . "\n";

        if ($operasional->isEmpty()) {
            $msg .= "    <i>Belum ada kategori</i>\n";
        } else {
            foreach ($operasional as $i => $acc) {
                $keyword = strtolower(str_replace(['Beban ', 'Biaya '], '', $acc->name));
                $num = $i + 1;
                $escapedName = $this->escapeHtml($acc->name);
                $msg .= "    {$num}. <code>{$keyword}</code> → {$escapedName}\n";
            }
        }

        $msg .= "\n";

        // Beban Bahan Baku
        $msg .= "🥩 <b>Beban Bahan Baku</b>\n"
              . "    <i>Gunakan dengan /beli</i>\n"
              . $this->lightSeparator() . "\n";

        if ($bahanBaku->isEmpty()) {
            $msg .= "    <i>Belum ada kategori</i>\n";
        } else {
            foreach ($bahanBaku as $i => $acc) {
                $keyword = strtolower(str_replace(['Beban ', 'Pembelian '], '', $acc->name));
                $num = $i + 1;
                $escapedName = $this->escapeHtml($acc->name);
                $msg .= "    {$num}. <code>{$keyword}</code> → {$escapedName}\n";
            }
        }

        $msg .= "\n" . $this->separator() . "\n"
              . "➕ Tambah kategori baru: /tambah";

        $this->sendMessage($chatId, $msg);
    }

    /**
     * Handle /ringkasan command - Today's summary with breakdown
     */
    protected function handleRingkasanCommand(string $chatId): void
    {
        $today = now()->toDateString();

        $totalBiaya = Expense::whereDate('date', $today)->sum('amount');
        $totalBeli = Purchase::whereDate('purchase_date', $today)->sum('total_amount');
        $countBiaya = Expense::whereDate('date', $today)->count();
        $countBeli = Purchase::whereDate('purchase_date', $today)->count();

        $msg = $this->formatBox('📊', 'RINGKASAN HARI INI', '📅 ' . now()->translatedFormat('l, d F Y')) . "\n\n";

        // Biaya Operasional section
        $msg .= "💳 <b>Biaya Operasional</b>\n";
        if ($countBiaya > 0) {
            $msg .= "    {$countBiaya} transaksi  •  <b>" . $this->formatRupiah($totalBiaya) . "</b>\n";

            // Breakdown per category
            $biayaBreakdown = Expense::whereDate('date', $today)
                ->selectRaw('chart_of_account_id, SUM(amount) as total, COUNT(*) as count')
                ->groupBy('chart_of_account_id')
                ->get();

            foreach ($biayaBreakdown as $item) {
                $accName = optional(ChartOfAccount::find($item->chart_of_account_id))->name ?? 'Lainnya';
                $escapedName = $this->escapeHtml($accName);
                $msg .= "    ├ {$escapedName}: " . $this->formatRupiah($item->total) . "\n";
            }
        } else {
            $msg .= "    <i>Belum ada transaksi</i>\n";
        }

        $msg .= "\n";

        // Pembelian Bahan section
        $msg .= "🛒 <b>Pembelian Bahan</b>\n";
        if ($countBeli > 0) {
            $msg .= "    {$countBeli} transaksi  •  <b>" . $this->formatRupiah($totalBeli) . "</b>\n";

            // Breakdown per category
            $beliBreakdown = Purchase::whereDate('purchase_date', $today)
                ->selectRaw('chart_of_account_id, SUM(total_amount) as total, COUNT(*) as count')
                ->groupBy('chart_of_account_id')
                ->get();

            foreach ($beliBreakdown as $item) {
                $accName = optional(ChartOfAccount::find($item->chart_of_account_id))->name ?? 'Lainnya';
                $escapedName = $this->escapeHtml($accName);
                $msg .= "    ├ {$escapedName}: " . $this->formatRupiah($item->total) . "\n";
            }
        } else {
            $msg .= "    <i>Belum ada transaksi</i>\n";
        }

        $msg .= "\n" . $this->separator() . "\n"
              . "📦 <b>TOTAL PENGELUARAN</b>\n"
              . "💰 <b>" . $this->formatRupiah($totalBiaya + $totalBeli) . "</b>\n"
              . $this->separator();

        $keyboard = [
            [
                ['text' => '🔄 Refresh', 'callback_data' => 'menu_ringkasan'],
                ['text' => '🏠 Menu Utama', 'callback_data' => 'menu_utama'],
            ],
        ];

        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    /**
     * Handle /start or /help command - Show main menu with buttons
     */
    protected function handleHelpCommand(string $chatId): void
    {
        $msg = "🍽️ <b>N-KITCHEN BOT</b>\n"
             . $this->separator() . "\n"
             . "Asisten Pencatatan Keuangan\n"
             . "Pempek N'Kitchen\n"
             . $this->separator() . "\n\n"
             . "📋 <b>PERINTAH CEPAT:</b>\n\n"
             . "  💳  <code>/biaya [kat] [jml] [desc]</code>\n"
             . "      <i>Catat biaya operasional</i>\n\n"
             . "  🛒  <code>/beli [kat] [jml] [desc]</code>\n"
             . "      <i>Catat pembelian bahan</i>\n\n"
             . "  ➕  <code>/tambah [jenis] [nama]</code>\n"
             . "      <i>Tambah kategori baru</i>\n\n"
             . $this->lightSeparator() . "\n"
             . "Atau gunakan tombol di bawah 👇";

        $keyboard = [
            [
                ['text' => '💳 Catat Biaya', 'callback_data' => 'menu_biaya'],
                ['text' => '🛒 Catat Pembelian', 'callback_data' => 'menu_beli'],
            ],
            [
                ['text' => '➕ Tambah Kategori', 'callback_data' => 'menu_tambah'],
                ['text' => '📂 Lihat Kategori', 'callback_data' => 'menu_kategori'],
            ],
            [
                ['text' => '📊 Ringkasan Hari Ini', 'callback_data' => 'menu_ringkasan'],
            ],
        ];

        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    // ─────────────────────────────────────────────
    //  SUB-MENU HANDLERS
    // ─────────────────────────────────────────────

    /**
     * Show biaya category selection menu
     */
    protected function showBiayaMenu(string $chatId): void
    {
        $accounts = ChartOfAccount::where('type', 'Expense')
            ->whereHas('parent', fn($q) => $q->where('name', 'like', '%Operasional%'))
            ->orderBy('code')
            ->get();

        if ($accounts->isEmpty()) {
            $msg = $this->formatHeader('📭', 'BELUM ADA KATEGORI') . "\n\n"
                 . "Belum ada kategori biaya operasional.\n"
                 . "Tambahkan dulu melalui menu\n"
                 . "<b>➕ Tambah Kategori</b>.";

            $keyboard = [
                [['text' => '➕ Tambah Kategori', 'callback_data' => 'menu_tambah']],
                [['text' => '🔙 Kembali', 'callback_data' => 'menu_utama']],
            ];
            $this->sendMessageWithButtons($chatId, $msg, $keyboard);
            return;
        }

        $msg = $this->formatHeader('💳', 'CATAT BIAYA OPERASIONAL') . "\n\n"
             . "📌 Pilih kategori biaya:";

        $keyboard = [];
        $row = [];

        foreach ($accounts as $index => $acc) {
            $shortName = str_replace(['Beban ', 'Biaya '], '', $acc->name);
            $row[] = ['text' => '📄 ' . $shortName, 'callback_data' => 'biaya_' . $acc->id];

            if (count($row) == 2 || $index == $accounts->count() - 1) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        $keyboard[] = [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'menu_utama']];

        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    /**
     * Show beli category selection menu
     */
    protected function showBeliMenu(string $chatId): void
    {
        $accounts = ChartOfAccount::where('type', 'Expense')
            ->whereHas('parent', fn($q) => $q->where('name', 'like', '%Bahan Baku%'))
            ->orderBy('code')
            ->get();

        if ($accounts->isEmpty()) {
            $msg = $this->formatHeader('📭', 'BELUM ADA KATEGORI') . "\n\n"
                 . "Belum ada kategori pembelian bahan.\n"
                 . "Tambahkan dulu melalui menu\n"
                 . "<b>➕ Tambah Kategori</b>.";

            $keyboard = [
                [['text' => '➕ Tambah Kategori', 'callback_data' => 'menu_tambah']],
                [['text' => '🔙 Kembali', 'callback_data' => 'menu_utama']],
            ];
            $this->sendMessageWithButtons($chatId, $msg, $keyboard);
            return;
        }

        $msg = $this->formatHeader('🛒', 'CATAT PEMBELIAN BAHAN') . "\n\n"
             . "📌 Pilih kategori pembelian:";

        $keyboard = [];
        $row = [];

        foreach ($accounts as $index => $acc) {
            $shortName = str_replace(['Beban ', 'Pembelian ', 'Bahan Baku '], '', $acc->name);
            $row[] = ['text' => '📄 ' . $shortName, 'callback_data' => 'beli_' . $acc->id];

            if (count($row) == 2 || $index == $accounts->count() - 1) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        $keyboard[] = [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'menu_utama']];

        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    /**
     * Show tambah category parent selection menu
     */
    protected function showTambahMenu(string $chatId): void
    {
        $msg = $this->formatHeader('➕', 'TAMBAH KATEGORI BARU') . "\n\n"
             . "📌 Pilih jenis kategori yang ingin\n"
             . "ditambahkan:";

        $keyboard = [
            [['text' => '🏢 Beban Operasional', 'callback_data' => 'tambah_operasional']],
            [['text' => '🥩 Beban Bahan Baku', 'callback_data' => 'tambah_bahan']],
            [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'menu_utama']],
        ];

        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    // ─────────────────────────────────────────────
    //  PROMPT HANDLERS
    // ─────────────────────────────────────────────

    /**
     * Prompt user to input biaya amount and description
     */
    protected function promptBiayaInput(string $chatId, string $accountId): void
    {
        $account = ChartOfAccount::find($accountId);
        if (!$account) return;

        $keyword = strtolower(str_replace(['Beban ', 'Biaya '], '', $account->name));
        $escapedName = $this->escapeHtml($account->name);

        $msg = $this->formatHeader('💳', 'CATAT BIAYA') . "\n\n"
             . "📁 Kategori: <b>{$escapedName}</b>\n\n"
             . "📋 <b>Ketik dengan format:</b>\n"
             . "<code>/biaya {$keyword} [jumlah] [keterangan]</code>\n\n"
             . "💡 <b>Contoh:</b>\n"
             . "<code>/biaya {$keyword} 500000 Pembayaran bulan ini</code>";

        $keyboard = [[['text' => '🔙 Kembali ke Kategori', 'callback_data' => 'menu_biaya']]];
        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    /**
     * Prompt user to input beli amount and description
     */
    protected function promptBeliInput(string $chatId, string $accountId): void
    {
        $account = ChartOfAccount::find($accountId);
        if (!$account) return;

        $keyword = strtolower(str_replace(['Beban ', 'Pembelian ', 'Bahan Baku '], '', $account->name));
        $escapedName = $this->escapeHtml($account->name);

        $msg = $this->formatHeader('🛒', 'CATAT PEMBELIAN') . "\n\n"
             . "📁 Kategori: <b>{$escapedName}</b>\n\n"
             . "📋 <b>Ketik dengan format:</b>\n"
             . "<code>/beli {$keyword} [jumlah] [keterangan]</code>\n\n"
             . "💡 <b>Contoh:</b>\n"
             . "<code>/beli {$keyword} 300000 Beli 10kg</code>";

        $keyboard = [[['text' => '🔙 Kembali ke Kategori', 'callback_data' => 'menu_beli']]];
        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    /**
     * Prompt user to input new category name
     */
    protected function promptTambahInput(string $chatId, string $parentType): void
    {
        $parentName = $parentType === 'operasional' ? 'Beban Operasional' : 'Beban Bahan Baku';
        $parentEmoji = $parentType === 'operasional' ? '🏢' : '🥩';

        $msg = $this->formatHeader('➕', 'TAMBAH KATEGORI') . "\n\n"
             . "{$parentEmoji} Parent: <b>{$parentName}</b>\n\n"
             . "📋 <b>Ketik dengan format:</b>\n"
             . "<code>/tambah {$parentType} [nama kategori]</code>\n\n"
             . "💡 <b>Contoh:</b>\n"
             . "<code>/tambah {$parentType} Biaya Internet</code>";

        $keyboard = [[['text' => '🔙 Kembali ke Jenis', 'callback_data' => 'menu_tambah']]];
        $this->sendMessageWithButtons($chatId, $msg, $keyboard);
    }

    // ─────────────────────────────────────────────
    //  TELEGRAM API METHODS
    // ─────────────────────────────────────────────

    /**
     * Send message to Telegram chat
     */
    public function sendMessage(string $chatId, string $message): void
    {
        try {
            Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to send message', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send message with inline keyboard buttons
     */
    public function sendMessageWithButtons(string $chatId, string $message, array $keyboard): void
    {
        try {
            Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => $keyboard]),
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to send message with buttons', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Answer callback query to remove loading indicator
     */
    protected function answerCallbackQuery(string $callbackId): void
    {
        try {
            Http::post("https://api.telegram.org/bot{$this->token}/answerCallbackQuery", [
                'callback_query_id' => $callbackId,
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to answer callback', ['error' => $e->getMessage()]);
        }
    }
}
