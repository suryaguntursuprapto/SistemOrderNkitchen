<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Message;
use App\Models\User;
use App\Models\Journal;
use App\Models\ChartOfAccount;
use App\Models\Purchase;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\Models\Expense;
use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Services\AccountingService;

class AdminController extends Controller
{
    protected $accountingService;
    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'unread_messages' => Message::unread()->count(),
            'total_revenue' => Order::whereIn('status', ['delivered'])->sum('total_amount'),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();
        $recent_messages = Message::with('user')->unread()->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_messages'));
    }

    // Menu Management
    public function menuIndex()
    {
        $menus = Menu::latest()->paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    public function menuCreate()
    {
        return view('admin.menu.create');
    }

    public function menuStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1', 
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-images', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function menuShow(Menu $menu)
    {
        return view('admin.menu.show', compact('menu'));
    }

    public function menuEdit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function menuUpdate(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1', 
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menu-images', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui!');
}

    public function menuDestroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    // Order Management
    public function orderIndex(Request $request)
    {
        $query = Order::with(['user', 'payment.paymentMethod', 'orderItems.menu']);

        // Search filter - by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Period filter (daily, weekly, monthly, yearly)
        if ($request->filled('period')) {
            $now = now();
            switch ($request->period) {
                case 'daily':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('created_at', $now->month)
                          ->whereYear('created_at', $now->year);
                    break;
                case 'yearly':
                    $query->whereYear('created_at', $now->year);
                    break;
            }
        }



        $orders = $query->latest()->paginate(10)->withQueryString();

        // Get counts for stats (without filters)
        $allOrders = Order::select('status')->get();
        $stats = [
            'total' => $allOrders->count(),
            'pending' => $allOrders->where('status', 'pending')->count(),
            'preparing' => $allOrders->where('status', 'preparing')->count(),
            'ready' => $allOrders->where('status', 'ready')->count(),
            'delivered' => $allOrders->where('status', 'delivered')->count(),
        ];

        return view('admin.order.index', compact('orders', 'stats'));
    }

    public function orderShow(Order $order)
    {
        $order->load(['user', 'orderItems.menu']);
        return view('admin.order.show', compact('order'));
    }

    public function orderUpdate(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $trackingNumberProvided = !empty($validated['tracking_number']);
        $trackingNumberIsNew = empty($order->tracking_number) && $trackingNumberProvided;
        $trackingNumberChanged = $trackingNumberProvided && ($order->tracking_number !== $validated['tracking_number']);

        // Auto-change status to 'ready' when tracking number is provided and order is not yet delivered/cancelled
        if ($trackingNumberProvided && !in_array($validated['status'], ['delivered', 'cancelled'])) {
            // Jika tracking number diisi, otomatis ubah status ke "ready" (Dalam Perjalanan)
            $validated['status'] = 'ready';
        }

        $order->update($validated);

        $successMessage = 'Pesanan berhasil diperbarui!';
        if ($trackingNumberIsNew || $trackingNumberChanged) {
            $successMessage = 'No. Resi berhasil disimpan! Status pesanan otomatis diubah ke "Dalam Perjalanan".';
        }

        return redirect()->route('admin.order.show', $order)->with('success', $successMessage);
    }

    public function orderDestroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.order.index')->with('success', 'Pesanan berhasil dihapus!');
    }

    // ==================== CHAT MANAGEMENT ====================

    /**
     * Display list of all conversations
     */
    public function messageIndex()
    {
        // Get all customers with their conversation info
        $customers = User::where('role', 'customer')
            ->with(['conversations' => function($q) {
                $q->where('status', 'open')
                  ->with('lastMessage')
                  ->withCount(['messages as unread_count' => function ($q) {
                      $q->where('sender_type', 'user')
                        ->where('is_read', false);
                  }]);
            }])
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                // Get the first open conversation or create placeholder data
                $conversation = $user->conversations->first();
                return (object)[
                    'user_id' => $user->id,
                    'user' => $user,
                    'conversation_id' => $conversation?->id,
                    'lastMessage' => $conversation?->lastMessage,
                    'last_message_at' => $conversation?->last_message_at,
                    'unread_count' => $conversation?->unread_count ?? 0,
                    'created_at' => $conversation?->created_at ?? $user->created_at,
                ];
            })
            // Sort by unread count (desc), then by last message (desc)
            ->sortByDesc('unread_count')
            ->sortByDesc('last_message_at');

        return view('admin.chat.index', ['conversations' => $customers]);
    }

    /**
     * Get chat messages for a specific conversation
     */
    public function getCustomerChat(User $user)
    {
        // Find or create conversation for this user
        $conversation = Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            return response()->json([
                'html' => '<div class="text-center text-gray-500 py-8">Belum ada percakapan dengan pelanggan ini.</div>',
                'user' => $user,
                'conversation_id' => null
            ]);
        }

        // Mark all user messages as read
        $conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'message_status' => 'read']);
        
        $conversation->update(['has_unread_admin' => false]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        
        return response()->json([
            'html' => view('admin.chat.partials.chat-bubble', compact('messages'))->render(),
            'user' => $user,
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * Send a reply to a conversation
     */
    public function sendChatReply(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Get or create conversation
        $conversation = Conversation::getOrCreateForUser($user->id);
        
        // Create admin message
        $message = Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'sender_type' => Message::SENDER_ADMIN,
            'sender_id' => auth()->id(),
            'subject' => 'Admin Reply',
            'message' => $request->message,
            'message_status' => Message::STATUS_SENT,
            'is_read' => false,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'has_unread_user' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Fetch new messages for polling (admin side)
     */
    public function fetchChatMessages(Request $request, User $user)
    {
        $lastId = $request->input('last_id', 0);
        
        $conversation = Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            return response()->json(['messages' => [], 'last_id' => 0]);
        }

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        $conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'message_status' => 'read']);

        $lastId = $messages->isNotEmpty() ? $messages->last()->id : $lastId;

        return response()->json([
            'messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_type' => $msg->sender_type,
                    'created_at' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                ];
            }),
            'last_id' => $lastId,
        ]);
    }

    /**
     * Clear chat history for a user
     */
    public function clearChat(User $user)
    {
        // Close all conversations for this user
        Conversation::where('user_id', $user->id)
            ->update(['status' => 'closed']);

        return response()->json(['status' => 'success', 'message' => 'Riwayat chat berhasil dihapus.']);
    }

    public function messageShow(Message $message)
    {
        $message->markAsRead();
        return view('admin.message.show', compact('message'));
    }

    public function messageReply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $message->update([
            'admin_reply' => $validated['admin_reply'],
            'replied_at' => now(),
            'is_read' => true,
        ]);

        return redirect()->route('admin.message.index')->with('success', 'Pesan berhasil dibalas!');
    }

    public function expenseIndex()
    {
        // Data 'with' sekarang harus mengambil relasi 'chartOfAccount'
        $expenses = Expense::with('chartOfAccount')->latest()->paginate(10);
        return view('admin.expense.index', compact('expenses'));
    }

    public function expenseCreate()
    {
        // Ambil semua akun COA yang tipenya 'Expense'
        $expenseAccounts = ChartOfAccount::where('type', 'Expense')
                                         ->orderBy('name')
                                         ->get();
                                         
        return view('admin.expense.create', compact('expenseAccounts'));
    }

    public function expenseStore(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            
            // Validasi baru, menggantikan 'category'
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id', 
        ]);

        $validated['user_id'] = auth()->id();

        $expense = Expense::create($validated);

        // ===== SAMBUNGAN AKUNTANSI (Sudah benar) =====
        $this->accountingService->recordExpense($expense);
        // ===========================================

        return redirect()->route('admin.expense.index')->with('success', 'Biaya berhasil dicatat.');
    }

    public function expenseDestroy(Expense $expense)
    {
        // TODO: Buat Jurnal Balik jika diperlukan
        $expense->journal()->delete(); // Hapus jurnal terkait
        $expense->delete();
        
        return redirect()->route('admin.expense.index')->with('success', 'Biaya berhasil dihapus.');
    }

    public function reportIndex(Request $request)
    {
        // Tetapkan tanggal default (bulan ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';

        // 1. Data Penjualan (dari Order)
        $salesData = Order::whereIn('status', ['delivered', 'confirmed', 'ready'])
                            ->whereBetween('created_at', [$startDateTime, $endDateTime])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        // 2. Data Biaya/Pembelian (INI BAGIAN YANG DIPERBARUI)
        
        // Ambil data Biaya (Expense)
        $expenseData = Expense::whereBetween('date', [$startDate, $endDate])->get();

        // Ambil data Pembelian (Purchase)
        $purchaseData = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->get();

        // 3. Kalkulasi Laba/Rugi
        $totalSales = $salesData->sum('total_amount');
        $totalExpenses = $expenseData->sum('amount');
        $totalPurchases = $purchaseData->sum('total_amount'); // <-- BARU
        
        $profit = $totalSales - ($totalExpenses + $totalPurchases); // <-- DIPERBARUI

        $summary = [
            'total_sales' => $totalSales,
            'total_expenses' => $totalExpenses + $totalPurchases, // <-- DIPERBARUI
            'profit' => $profit,
        ];

        // 4. GABUNGKAN DATA BIAYA & PEMBELIAN UNTUK TABEL
        
        // Ubah format Biaya
        $expenses = $expenseData->map(function ($item) {
            return (object) [
                'date' => $item->date,
                'description' => $item->description,
                'category' => $item->category ?? 'Biaya Operasional',
                'amount' => $item->amount,
                'type' => 'expense' // Tandai sebagai 'expense'
            ];
        });

        // Ubah format Pembelian
        $purchases = $purchaseData->map(function ($item) {
            return (object) [
                'date' => $item->purchase_date,
                'description' => 'Pembelian: ' . ($item->supplier_name ?? $item->invoice_number ?? 'ID ' . $item->id),
                'category' => 'Pembelian Bahan Baku',
                'amount' => $item->total_amount,
                'type' => 'purchase' // Tandai sebagai 'purchase'
            ];
        });

        // Gabungkan dan urutkan berdasarkan tanggal
        $combinedCosts = collect($expenses)->merge($purchases)->sortBy('date');

        return view('admin.report.index', compact(
            'summary', 
            'salesData', 
            'combinedCosts', // <-- KIRIM DATA GABUNGAN (BUKAN expenseData)
            'startDate', 
            'endDate'
        ));
    }

    public function reportExport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'laporan_keuangan_' . $startDate . '_sd_' . $endDate . '.xlsx';

        return Excel::download(new FinancialReportExport($startDate, $endDate), $fileName);
    }

    /**
     * 📖 Menampilkan Jurnal Umum (Semua Transaksi)
     */
    public function journalIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $journals = Journal::with('transactions.chartOfAccount', 'referenceable')
                            ->whereBetween('date', [$startDate, $endDate])
                            ->latest()
                            ->paginate(20);

        return view('admin.report.journal', compact('journals', 'startDate', 'endDate'));
    }

    /**
     * 📚 Menampilkan Buku Besar (Transaksi per Akun)
     */
    public function ledgerIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil semua akun yang punya transaksi di rentang tanggal ini
        $accounts = ChartOfAccount::whereHas('journalTransactions', function ($query) use ($startDate, $endDate) {
            $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                $subQuery->whereBetween('date', [$startDate, $endDate]);
            });
        })
        ->with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                $subQuery->whereBetween('date', [$startDate, $endDate]);
            })->with('journal');
        }])
        ->get();

        return view('admin.report.ledger', compact('accounts', 'startDate', 'endDate'));
    }
}