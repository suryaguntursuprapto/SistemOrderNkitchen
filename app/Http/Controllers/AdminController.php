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
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\Models\Expense;
use App\Exports\FinancialReportExport;
use App\Exports\JournalExport;
use App\Exports\LedgerExport;
use App\Exports\TrialBalanceExport;
use App\Exports\IncomeStatementExport;
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
        // Calculate Monthly Profit/Loss from Journal (same as Income Statement)
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();

        // Get revenue from pendapatan accounts (same as income statement)
        $revenueAccounts = ChartOfAccount::where('type', 'pendapatan')
            ->orWhere('type', 'revenue')
            ->with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->get();

        $totalRevenue = 0;
        foreach ($revenueAccounts as $account) {
            $balance = $account->journalTransactions->sum('credit') - $account->journalTransactions->sum('debit');
            $totalRevenue += $balance;
        }

        // Get expenses from beban accounts (same as income statement)
        $expenseAccounts = ChartOfAccount::where('type', 'beban')
            ->orWhere('type', 'expense')
            ->with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->get();

        $totalExpenses = 0;
        foreach ($expenseAccounts as $account) {
            $balance = $account->journalTransactions->sum('debit') - $account->journalTransactions->sum('credit');
            $totalExpenses += $balance;
        }

        $monthlyProfit = $totalRevenue - $totalExpenses;

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'unread_messages' => Message::unread()->count(),
            'monthly_profit' => $monthlyProfit, // Laba/Rugi bulan ini dari jurnal
            'current_month' => Carbon::now()->translatedFormat('F Y'),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();
        $recent_messages = Message::with('user')->unread()->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_messages'));
    }

    // Menu Management
    public function menuIndex(Request $request)
    {
        $query = Menu::latest();
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $menus = $query->paginate(10);
        $categories = Category::active()->ordered()->get();
        
        return view('admin.menu.index', compact('menus', 'categories'));
    }

    public function menuCreate()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function menuStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1', 
            'category_id' => 'required|exists:categories,id',
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
        $categories = Category::active()->ordered()->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function menuUpdate(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1', 
            'category_id' => 'required|exists:categories,id',
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
        // Cek apakah menu sudah pernah dipesan
        if ($menu->orderItems()->exists()) {
            return redirect()->route('admin.menu.index')
                ->with('error', 'Menu tidak dapat dihapus karena sudah pernah dipesan oleh customer. Nonaktifkan menu ini sebagai gantinya.');
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    // ==================== CATEGORY MANAGEMENT ====================

    public function categoryIndex()
    {
        $categories = Category::withCount('menus')->ordered()->paginate(15);
        return view('admin.category.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('admin.category.create');
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.category.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryEdit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.category.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoryDestroy(Category $category)
    {
        // Cek apakah kategori masih memiliki menu
        if ($category->menus()->exists()) {
            return redirect()->route('admin.category.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->menus()->count() . ' menu. Pindahkan atau hapus menu terlebih dahulu.');
        }
        
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Kategori berhasil dihapus!');
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
        $order->load(['user', 'orderItems.menu', 'payments']);
        return view('admin.order.show', compact('order'));
    }

    public function orderShippingLabel(Order $order)
    {
        $order->load(['user', 'orderItems.menu']);
        return view('admin.order.shipping-label', compact('order'));
    }

    /**
     * Get order tracking information from Biteship
     */
    public function orderTracking(Order $order)
    {
        $biteshipService = app(\App\Services\BiteshipService::class);
        
        // Instant couriers (gosend, grab) need order ID not waybill
        $instantCouriers = ['gosend', 'grab', 'gojek'];
        $isInstantCourier = in_array(strtolower($order->courier ?? ''), $instantCouriers);
        
        // For instant couriers, use biteship_order_id; for others use waybill
        if ($isInstantCourier) {
            $trackingId = $order->biteship_order_id;
            if (empty($trackingId)) {
                $trackingId = $order->biteship_waybill_id ?? $order->tracking_number;
            }
            
            // If instant courier has no tracking ID, return informative response
            if (empty($trackingId)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status' => 'waiting',
                        'courier' => strtoupper($order->courier),
                        'is_instant' => true,
                        'history' => [
                            [
                                'note' => 'Kurir instan ('. strtoupper($order->courier) .') akan menjemput pesanan segera.',
                                'updated_at' => now()->toIso8601String()
                            ],
                            [
                                'note' => 'Tracking real-time akan tersedia setelah kurir mengambil paket.',
                                'updated_at' => now()->subMinute()->toIso8601String()
                            ]
                        ]
                    ]
                ]);
            }
        } else {
            $trackingId = $order->biteship_waybill_id ?? $order->tracking_number;
        }
        
        if (empty($trackingId)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor resi belum tersedia untuk pesanan ini.'
            ]);
        }
        
        if (empty($order->courier)) {
            return response()->json([
                'success' => false,
                'message' => 'Informasi kurir tidak tersedia.'
            ]);
        }
        
        // Get tracking from Biteship
        $tracking = $biteshipService->getTracking($trackingId, $order->courier);
        
        if (!$tracking) {
            // For instant couriers, return a helpful message instead of error
            if ($isInstantCourier) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status' => 'processing',
                        'courier' => strtoupper($order->courier),
                        'is_instant' => true,
                        'history' => [
                            [
                                'note' => 'Pesanan sedang diproses. Kurir akan segera menjemput.',
                                'updated_at' => now()->toIso8601String()
                            ]
                        ]
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengambil informasi tracking. Silakan coba lagi nanti.'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $tracking
        ]);
    }

    /**
     * Manually create Biteship order when items are ready (for PO/Pre-order items)
     */
    public function createBiteshipOrder(Order $order)
    {
        // Check if order already has Biteship order
        if ($order->biteship_order_id) {
            return back()->with('error', 'Order sudah memiliki Biteship Order ID: ' . $order->biteship_order_id);
        }
        
        // Check if payment is confirmed
        $payment = $order->payments()->first();
        if (!$payment || $payment->status !== 'confirmed') {
            return back()->with('error', 'Pembayaran belum dikonfirmasi. Tidak dapat membuat order Biteship.');
        }
        
        // Create Biteship order
        $biteshipService = app(\App\Services\BiteshipService::class);
        $result = $biteshipService->createOrder($order);
        
        if ($result && isset($result['success']) && $result['success']) {
            \Log::info('Admin manually created Biteship order', [
                'order_id' => $order->id,
                'biteship_order_id' => $order->biteship_order_id
            ]);
            
            return back()->with('success', 'Order Biteship berhasil dibuat! AWB: ' . ($order->biteship_waybill_id ?? 'Menunggu...'));
        }
        
        $errorMessage = $result['error'] ?? 'Gagal membuat order Biteship';
        \Log::warning('Admin failed to create Biteship order', [
            'order_id' => $order->id,
            'error' => $errorMessage
        ]);
        
        return back()->with('error', 'Gagal membuat order Biteship: ' . $errorMessage);
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

    /**
     * Process manual refund for an order
     */
    public function orderRefund(Request $request, Order $order)
    {
        // Validate that order has a payment and is eligible for refund
        if (!$order->payment) {
            return back()->with('error', 'Pesanan ini tidak memiliki data pembayaran.');
        }

        $paymentStatus = $order->payment->status;
        $eligibleStatuses = ['paid', 'settlement', 'capture', 'confirmed'];
        
        if (!in_array($paymentStatus, $eligibleStatuses)) {
            return back()->with('error', 'Pesanan ini tidak dapat di-refund. Status pembayaran: ' . $paymentStatus);
        }

        try {
            $midtransService = app(\App\Services\MidtransService::class);
            $midtransOrderId = $order->payment->midtrans_order_id ?? "ORDER-{$order->id}";
            $refundAmount = $order->total_amount;

            // Attempt refund via Midtrans
            $refundResult = null;
            try {
                $refundResult = $midtransService->refundTransaction(
                    $midtransOrderId,
                    $refundAmount,
                    'Refund manual oleh admin'
                );
            } catch (\Exception $e) {
                \Log::warning("Midtrans refund API error for order #{$order->id}: " . $e->getMessage());
                // Continue anyway - admin can process manual bank transfer
            }

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                           "[REFUND] Dana dikembalikan oleh admin. " .
                           "Waktu: " . now()->format('d/m/Y H:i:s') . " WIB. " .
                           ($refundResult ? "Refund Midtrans berhasil." : "Perlu transfer manual ke customer.")
            ]);

            // Update payment status
            $order->payment->update([
                'status' => 'refunded',
                'notes' => ($order->payment->notes ? $order->payment->notes . "\n" : '') .
                           "Refund diproses oleh admin pada " . now()->format('d/m/Y H:i:s')
            ]);

            // Send notification to customer
            $this->sendRefundNotificationToCustomer($order);

            $successMessage = $refundResult 
                ? 'Refund berhasil diproses via Midtrans! Dana akan dikirim ke customer dalam 3-14 hari kerja.'
                : 'Status refund berhasil diupdate. Silakan transfer manual ke rekening customer.';

            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            \Log::error("Refund error for order #{$order->id}: " . $e->getMessage());
            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
        }
    }

    /**
     * Send refund notification to customer
     */
    private function sendRefundNotificationToCustomer(Order $order)
    {
        try {
            $phone = $order->phone;
            if (!$phone) return;

            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            }

            $message = "🔄 *Notifikasi Refund - N-Kitchen*\n\n";
            $message .= "Halo {$order->recipient_name},\n\n";
            $message .= "Pesanan Anda dengan nomor *#{$order->order_number}* telah kami batalkan dan dana akan dikembalikan.\n\n";
            $message .= "📋 *Detail:*\n";
            $message .= "• No. Order: {$order->order_number}\n";
            $message .= "• Total Refund: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
            $message .= "💰 *Pengembalian Dana:*\n";
            $message .= "Dana akan dikembalikan ke metode pembayaran Anda dalam 3-14 hari kerja.\n\n";
            $message .= "Terima kasih atas pengertiannya. 🙏";

            $apiKey = config('services.wapisender.api_key');
            $deviceKey = config('services.wapisender.device_key');

            if ($apiKey && $deviceKey) {
                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $apiKey,
                ])->post('https://api.wapisender.id/api/v1/message/send-message', [
                    'device_key' => $deviceKey,
                    'destination' => $phone,
                    'message' => $message,
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to send refund notification: " . $e->getMessage());
        }
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
        // Ambil akun COA beban yang merupakan sub-kategori dari "Beban Operasional"
        // untuk pemilihan jenis biaya (Biaya Gaji, Biaya Listrik, dll)
        $expenseAccounts = ChartOfAccount::where('type', 'Expense')
                                         ->whereHas('parent', function ($query) {
                                             $query->where('name', 'like', '%Operasional%');
                                         })
                                         ->orderBy('code')
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
        ->orderBy('code', 'asc')
        ->get();

        return view('admin.report.ledger', compact('accounts', 'startDate', 'endDate'));
    }

    /**
     * 📥 Export Jurnal Umum ke Excel
     */
    public function journalExport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'jurnal_umum_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new JournalExport($startDate, $endDate), $fileName);
    }

    /**
     * 📥 Export Buku Besar ke Excel
     */
    public function ledgerExport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'buku_besar_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new LedgerExport($startDate, $endDate), $fileName);
    }

    /**
     * ⚖️ Menampilkan Neraca Saldo
     */
    public function trialBalanceIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $accounts = ChartOfAccount::with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                $subQuery->whereBetween('date', [$startDate, $endDate]);
            });
        }])
        ->orderBy('code', 'asc')
        ->get();

        // Calculate balances
        $trialBalance = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $sumDebit = $account->journalTransactions->sum('debit');
            $sumCredit = $account->journalTransactions->sum('credit');

            if ($sumDebit == 0 && $sumCredit == 0) {
                continue;
            }

            $isDebitNormal = $account->normal_balance === 'debit';
            $openingBalance = $account->opening_balance ?? 0;
            
            if ($isDebitNormal) {
                $balance = $openingBalance + $sumDebit - $sumCredit;
                $debitBalance = $balance > 0 ? $balance : 0;
                $creditBalance = $balance < 0 ? abs($balance) : 0;
            } else {
                $balance = $openingBalance + $sumCredit - $sumDebit;
                $creditBalance = $balance > 0 ? $balance : 0;
                $debitBalance = $balance < 0 ? abs($balance) : 0;
            }

            $totalDebit += $debitBalance;
            $totalCredit += $creditBalance;

            $trialBalance[] = [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => $debitBalance,
                'credit' => $creditBalance,
            ];
        }

        return view('admin.report.trial-balance', compact('trialBalance', 'totalDebit', 'totalCredit', 'startDate', 'endDate'));
    }

    /**
     * 📥 Export Neraca Saldo ke Excel
     */
    public function trialBalanceExport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'neraca_saldo_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new TrialBalanceExport($startDate, $endDate), $fileName);
    }

    /**
     * 📈 Menampilkan Laporan Laba Rugi Detail
     */
    public function incomeStatementIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Revenue accounts
        $revenueAccounts = ChartOfAccount::where('type', 'pendapatan')
            ->orWhere('type', 'revenue')
            ->with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->orderBy('code', 'asc')
            ->get();

        // Expense accounts
        $expenseAccounts = ChartOfAccount::where('type', 'beban')
            ->orWhere('type', 'expense')
            ->with(['journalTransactions' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->orderBy('code', 'asc')
            ->get();

        // Calculate revenue
        $revenues = [];
        $totalRevenue = 0;
        foreach ($revenueAccounts as $account) {
            $balance = $account->journalTransactions->sum('credit') - $account->journalTransactions->sum('debit');
            if ($balance != 0) {
                $totalRevenue += $balance;
                $revenues[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
            }
        }

        // Calculate expenses
        $expenses = [];
        $totalExpenses = 0;
        foreach ($expenseAccounts as $account) {
            $balance = $account->journalTransactions->sum('debit') - $account->journalTransactions->sum('credit');
            if ($balance != 0) {
                $totalExpenses += $balance;
                $expenses[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
            }
        }

        $netIncome = $totalRevenue - $totalExpenses;

        return view('admin.report.income-statement', compact(
            'revenues', 'expenses', 'totalRevenue', 'totalExpenses', 'netIncome', 'startDate', 'endDate'
        ));
    }

    /**
     * 📥 Export Laporan Laba Rugi ke Excel
     */
    public function incomeStatementExport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'laporan_laba_rugi_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new IncomeStatementExport($startDate, $endDate), $fileName);
    }
}