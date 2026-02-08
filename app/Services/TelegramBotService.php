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
            $this->sendMessage($chatId, "⛔ Anda tidak memiliki akses ke bot ini.");
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

    /**
     * Handle /biaya command - Input operational expense
     * Format: /biaya [kategori] [jumlah] [deskripsi]
     */
    protected function handleBiayaCommand(string $chatId, string $text): void
    {
        // Parse: /biaya gaji 500000 Gaji karyawan
        $parts = explode(' ', $text, 4);
        
        if (count($parts) < 3) {
            $this->sendMessage($chatId, "❌ Format salah!\n\nContoh:\n`/biaya gaji 500000 Gaji bulan Feb`");
            return;
        }

        $kategori = strtolower($parts[1]);
        $jumlah = floatval(preg_replace('/[^0-9.]/', '', $parts[2]));
        $deskripsi = $parts[3] ?? ucfirst($kategori);

        if ($jumlah <= 0) {
            $this->sendMessage($chatId, "❌ Jumlah harus lebih dari 0!");
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
            $this->sendMessage($chatId, "❌ Kategori '$kategori' tidak ditemukan di Beban Operasional.\n\nGunakan `/kategori` untuk melihat daftar.");
            return;
        }

        try {
            // Create expense
            $expense = Expense::create([
                'description' => $deskripsi,
                'amount' => $jumlah,
                'date' => now()->toDateString(),
                'chart_of_account_id' => $account->id,
                'user_id' => 1, // Admin
            ]);

            // Record to journal
            $this->accountingService->recordExpense($expense);

            $formatted = number_format($jumlah, 0, ',', '.');
            $this->sendMessage($chatId, "✅ *Biaya Tercatat*\n\n📁 Kategori: {$account->name}\n💰 Jumlah: Rp {$formatted}\n📝 Deskripsi: {$deskripsi}");

            Log::info('Telegram: Expense recorded', ['expense_id' => $expense->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to record expense', ['error' => $e->getMessage()]);
            $this->sendMessage($chatId, "❌ Gagal menyimpan biaya: " . $e->getMessage());
        }
    }

    /**
     * Handle /beli command - Input purchase
     * Format: /beli [kategori] [jumlah] [deskripsi]
     */
    protected function handleBeliCommand(string $chatId, string $text): void
    {
        // Parse: /beli ikan 300000 Ikan tenggiri 10kg
        $parts = explode(' ', $text, 4);
        
        if (count($parts) < 3) {
            $this->sendMessage($chatId, "❌ Format salah!\n\nContoh:\n`/beli ikan 300000 Ikan tenggiri 10kg`");
            return;
        }

        $kategori = strtolower($parts[1]);
        $jumlah = floatval(preg_replace('/[^0-9.]/', '', $parts[2]));
        $deskripsi = $parts[3] ?? ucfirst($kategori);

        if ($jumlah <= 0) {
            $this->sendMessage($chatId, "❌ Jumlah harus lebih dari 0!");
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
            $this->sendMessage($chatId, "❌ Kategori '$kategori' tidak ditemukan di Beban Bahan Baku.\n\nGunakan `/kategori` untuk melihat daftar.");
            return;
        }

        try {
            // Create simple purchase (single item)
            $purchase = Purchase::create([
                'purchase_date' => now()->toDateString(),
                'supplier_name' => 'Via Telegram',
                'invoice_number' => 'TG-' . now()->format('YmdHis'),
                'chart_of_account_id' => $account->id,
                'status' => 'paid',
                'notes' => $deskripsi,
                'total_amount' => $jumlah,
            ]);

            // Create single purchase detail
            $purchase->purchaseDetails()->create([
                'item_name' => $deskripsi,
                'quantity' => 1,
                'unit' => 'pcs',
                'price_per_unit' => $jumlah,
                'subtotal' => $jumlah,
            ]);

            // Record to journal
            $this->accountingService->recordPurchase($purchase);

            $formatted = number_format($jumlah, 0, ',', '.');
            $this->sendMessage($chatId, "✅ *Pembelian Tercatat*\n\n📁 Kategori: {$account->name}\n💰 Jumlah: Rp {$formatted}\n📝 Deskripsi: {$deskripsi}");

            Log::info('Telegram: Purchase recorded', ['purchase_id' => $purchase->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to record purchase', ['error' => $e->getMessage()]);
            $this->sendMessage($chatId, "❌ Gagal menyimpan pembelian: " . $e->getMessage());
        }
    }

    /**
     * Handle /tambah command - Create new COA subcategory
     * Format: /tambah [parent] [nama]
     */
    protected function handleTambahCommand(string $chatId, string $text): void
    {
        // Parse: /tambah operasional Biaya Internet
        $parts = explode(' ', $text, 3);
        
        if (count($parts) < 3) {
            $this->sendMessage($chatId, "❌ Format salah!\n\nContoh:\n`/tambah operasional Biaya Internet`\n`/tambah bahan Pembelian Minyak`");
            return;
        }

        $parentKeyword = strtolower($parts[1]);
        $namaSubkategori = $parts[2];

        // Find parent account
        $parent = ChartOfAccount::where('type', 'Expense')
            ->whereNull('parent_id')
            ->where(function ($q) use ($parentKeyword) {
                $q->where('name', 'like', '%' . $parentKeyword . '%');
            })
            ->first();

        if (!$parent) {
            $this->sendMessage($chatId, "❌ Parent '$parentKeyword' tidak ditemukan.\n\nGunakan:\n- `operasional` untuk Beban Operasional\n- `bahan` untuk Beban Bahan Baku");
            return;
        }

        // Generate next code
        $lastChild = ChartOfAccount::where('parent_id', $parent->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastChild) {
            $nextCode = (int)$lastChild->code + 1;
        } else {
            $nextCode = (int)$parent->code + 1;
        }

        try {
            $newAccount = ChartOfAccount::create([
                'code' => (string)$nextCode,
                'name' => $namaSubkategori,
                'type' => 'Expense',
                'normal_balance' => 'Debit',
                'parent_id' => $parent->id,
                'description' => 'Dibuat via Telegram Bot',
            ]);

            $this->sendMessage($chatId, "✅ *Kategori Baru Dibuat*\n\n🔢 Kode: {$newAccount->code}\n📁 Nama: {$namaSubkategori}\n📂 Parent: {$parent->name}");

            Log::info('Telegram: New COA created', ['account_id' => $newAccount->id]);
        } catch (\Exception $e) {
            Log::error('Telegram: Failed to create COA', ['error' => $e->getMessage()]);
            $this->sendMessage($chatId, "❌ Gagal membuat kategori: " . $e->getMessage());
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

        $message = "*📂 DAFTAR KATEGORI*\n\n";
        
        $message .= "*Beban Operasional* (untuk /biaya):\n";
        if ($operasional->isEmpty()) {
            $message .= "  - Belum ada\n";
        } else {
            foreach ($operasional as $acc) {
                $keyword = strtolower(str_replace(['Beban ', 'Biaya '], '', $acc->name));
                $message .= "  • `{$keyword}` → {$acc->name}\n";
            }
        }

        $message .= "\n*Beban Bahan Baku* (untuk /beli):\n";
        if ($bahanBaku->isEmpty()) {
            $message .= "  - Belum ada\n";
        } else {
            foreach ($bahanBaku as $acc) {
                $keyword = strtolower(str_replace(['Beban ', 'Pembelian '], '', $acc->name));
                $message .= "  • `{$keyword}` → {$acc->name}\n";
            }
        }

        $this->sendMessage($chatId, $message);
    }

    /**
     * Handle /ringkasan command - Today's summary
     */
    protected function handleRingkasanCommand(string $chatId): void
    {
        $today = now()->toDateString();

        $totalBiaya = Expense::whereDate('date', $today)->sum('amount');
        $totalBeli = Purchase::whereDate('purchase_date', $today)->sum('total_amount');
        $countBiaya = Expense::whereDate('date', $today)->count();
        $countBeli = Purchase::whereDate('purchase_date', $today)->count();

        $fBiaya = number_format($totalBiaya, 0, ',', '.');
        $fBeli = number_format($totalBeli, 0, ',', '.');
        $fTotal = number_format($totalBiaya + $totalBeli, 0, ',', '.');

        $message = "*📊 RINGKASAN HARI INI*\n";
        $message .= "📅 " . now()->translatedFormat('l, d F Y') . "\n\n";
        $message .= "💳 Biaya Operasional: {$countBiaya}x = Rp {$fBiaya}\n";
        $message .= "🛒 Pembelian Bahan: {$countBeli}x = Rp {$fBeli}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "📦 *Total Pengeluaran: Rp {$fTotal}*";

        $this->sendMessage($chatId, $message);
    }

    /**
     * Handle /start or /help command - Show main menu with buttons
     */
    protected function handleHelpCommand(string $chatId): void
    {
        $message = "👋 *Selamat datang di N-Kitchen Bot!*\n\n";
        $message .= "Pilih menu di bawah atau ketik perintah manual:\n\n";
        $message .= "`/biaya [kat] [jml] [desc]`\n";
        $message .= "`/beli [kat] [jml] [desc]`\n";
        $message .= "`/tambah [parent] [nama]`";

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

        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

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
            $this->sendMessage($chatId, "❌ Belum ada kategori biaya. Tambahkan dulu via menu Tambah Kategori.");
            return;
        }

        $message = "💳 *CATAT BIAYA OPERASIONAL*\n\nPilih kategori:";
        $keyboard = [];
        $row = [];

        foreach ($accounts as $index => $acc) {
            $shortName = str_replace(['Beban ', 'Biaya '], '', $acc->name);
            $row[] = ['text' => $shortName, 'callback_data' => 'biaya_' . $acc->id];
            
            if (count($row) == 2 || $index == $accounts->count() - 1) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        $keyboard[] = [['text' => '🔙 Kembali', 'callback_data' => 'menu_utama']];

        $this->sendMessageWithButtons($chatId, $message, $keyboard);
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
            $this->sendMessage($chatId, "❌ Belum ada kategori pembelian. Tambahkan dulu via menu Tambah Kategori.");
            return;
        }

        $message = "🛒 *CATAT PEMBELIAN BAHAN*\n\nPilih kategori:";
        $keyboard = [];
        $row = [];

        foreach ($accounts as $index => $acc) {
            $shortName = str_replace(['Beban ', 'Pembelian ', 'Bahan Baku '], '', $acc->name);
            $row[] = ['text' => $shortName, 'callback_data' => 'beli_' . $acc->id];
            
            if (count($row) == 2 || $index == $accounts->count() - 1) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        $keyboard[] = [['text' => '🔙 Kembali', 'callback_data' => 'menu_utama']];

        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

    /**
     * Show tambah category parent selection menu
     */
    protected function showTambahMenu(string $chatId): void
    {
        $message = "➕ *TAMBAH KATEGORI BARU*\n\nPilih jenis kategori:";

        $keyboard = [
            [['text' => '🏢 Beban Operasional', 'callback_data' => 'tambah_operasional']],
            [['text' => '🥩 Beban Bahan Baku', 'callback_data' => 'tambah_bahan']],
            [['text' => '🔙 Kembali', 'callback_data' => 'menu_utama']],
        ];

        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

    /**
     * Prompt user to input biaya amount and description
     */
    protected function promptBiayaInput(string $chatId, string $accountId): void
    {
        $account = ChartOfAccount::find($accountId);
        if (!$account) return;

        $keyword = strtolower(str_replace(['Beban ', 'Biaya '], '', $account->name));
        $message = "💳 *Catat Biaya: {$account->name}*\n\n";
        $message .= "Ketik dengan format:\n";
        $message .= "`/biaya {$keyword} [jumlah] [keterangan]`\n\n";
        $message .= "Contoh:\n";
        $message .= "`/biaya {$keyword} 500000 Pembayaran bulan ini`";

        $keyboard = [[['text' => '🔙 Kembali', 'callback_data' => 'menu_biaya']]];
        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

    /**
     * Prompt user to input beli amount and description
     */
    protected function promptBeliInput(string $chatId, string $accountId): void
    {
        $account = ChartOfAccount::find($accountId);
        if (!$account) return;

        $keyword = strtolower(str_replace(['Beban ', 'Pembelian ', 'Bahan Baku '], '', $account->name));
        $message = "🛒 *Catat Pembelian: {$account->name}*\n\n";
        $message .= "Ketik dengan format:\n";
        $message .= "`/beli {$keyword} [jumlah] [keterangan]`\n\n";
        $message .= "Contoh:\n";
        $message .= "`/beli {$keyword} 300000 Beli 10kg`";

        $keyboard = [[['text' => '🔙 Kembali', 'callback_data' => 'menu_beli']]];
        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

    /**
     * Prompt user to input new category name
     */
    protected function promptTambahInput(string $chatId, string $parentType): void
    {
        $parentName = $parentType === 'operasional' ? 'Beban Operasional' : 'Beban Bahan Baku';
        $message = "➕ *Tambah Kategori: {$parentName}*\n\n";
        $message .= "Ketik dengan format:\n";
        $message .= "`/tambah {$parentType} [nama kategori]`\n\n";
        $message .= "Contoh:\n";
        $message .= "`/tambah {$parentType} Biaya Internet`";

        $keyboard = [[['text' => '🔙 Kembali', 'callback_data' => 'menu_tambah']]];
        $this->sendMessageWithButtons($chatId, $message, $keyboard);
    }

    /**
     * Send message to Telegram chat
     */
    public function sendMessage(string $chatId, string $message): void
    {
        try {
            Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
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
                'parse_mode' => 'Markdown',
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
