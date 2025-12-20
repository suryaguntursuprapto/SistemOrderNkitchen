<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Message;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Category;
use App\Services\MidtransService;
use App\Services\AccountingService;
use App\Services\BiteshipService; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $midtransService;
    protected $accountingService;
    protected $biteshipService;

    public function __construct(MidtransService $midtransService, AccountingService $accountingService, BiteshipService $biteshipService)
    {
        $this->midtransService = $midtransService;
        $this->accountingService = $accountingService;
        $this->biteshipService = $biteshipService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isCustomer()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    // --- API WILAYAH & ONGKIR (BITESHIP) ---

    private function getBiteshipHeaders()
    {
        return [
            'Authorization' => config('services.biteship.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * ❌ DEPRECATED: Tidak didukung Komerce.
     */
    public function getProvinces()
    {
         Log::warning('Deprecated API Call: getProvinces not supported by Komerce. Use search.');
         return response()->json(['error' => 'Komerce API requires Search for regions.'], 500); 
    }

    /**
     * ❌ DEPRECATED: Tidak didukung Komerce.
     */
    public function getCities($province_id)
    {
         Log::warning('Deprecated API Call: getCities not supported by Komerce. Use search.');
         return response()->json(['error' => 'Komerce API requires Search for regions.'], 500);
    }
    
    /**
     * Calculate Shipping Cost.
     */
    // app/Http/Controllers/CustomerController.php
    // app/Http/Controllers/CustomerController.php (di dalam checkShippingCost)

    public function checkShippingCost(Request $request)
    {
        $payload = $request->json();

        $originPostalCode = config('services.biteship.origin_postal_code');
        $originLatitude = config('services.biteship.origin_latitude');
        $originLongitude = config('services.biteship.origin_longitude');
        
        // Baca data dari payload JSON:
        $destinationPostalCode = $payload->get('destination_postal_code');
        $destinationAreaId = $payload->get('destination_area_id');
        $destinationLatitude = $payload->get('destination_latitude');
        $destinationLongitude = $payload->get('destination_longitude');
        $weight = max(1, (int)$payload->get('weight')); 
        $courier = $payload->get('courier');
        $itemValue = $payload->get('item_value') ?? 0;

        \Log::info('SHIPPING CHECK: Payload received', [
            'origin_postal_code' => $originPostalCode,
            'origin_coords' => "{$originLatitude}, {$originLongitude}",
            'destination_postal_code' => $destinationPostalCode,
            'destination_area_id' => $destinationAreaId,
            'destination_coords' => "{$destinationLatitude}, {$destinationLongitude}",
            'courier' => $courier,
            'weight' => $weight,
        ]);
        
        // Validasi destination
        if (empty($destinationPostalCode) && empty($destinationAreaId) && (empty($destinationLatitude) || empty($destinationLongitude))) {
            \Log::warning('SHIPPING ABORTED: Destination postal code, area ID, or coordinates are missing.');
            return response()->json(['error' => 'Pilih kota/kecamatan tujuan terlebih dahulu.'], 400); 
        }

        if (empty($courier)) {
            \Log::warning('SHIPPING ABORTED: Courier is missing in payload.');
            return response()->json(['error' => 'Pilih kurir pengiriman.'], 400); 
        }

        try {
            $endpoint = config('services.biteship.base_url') . '/v1/rates/couriers';
            
            // Map courier code ke format Biteship
            $courierCodes = $this->mapCourierToBiteship($courier);
            
            // Cek apakah ini kurir instant (gojek, grab)
            $isInstantCourier = in_array($courierCodes, ['gojek', 'grab']);
            
            // Build request body
            $requestBody = [
                'couriers' => $courierCodes,
                'items' => [
                    [
                        'name' => 'N-Kitchen',
                        'value' => (int)$itemValue,
                        'weight' => (int)$weight,
                        'quantity' => 1
                    ]
                ]
            ];

            // Untuk instant couriers, gunakan koordinat
            if ($isInstantCourier && $destinationLatitude && $destinationLongitude && $originLatitude && $originLongitude) {
                $requestBody['origin_latitude'] = (float)$originLatitude;
                $requestBody['origin_longitude'] = (float)$originLongitude;
                $requestBody['destination_latitude'] = (float)$destinationLatitude;
                $requestBody['destination_longitude'] = (float)$destinationLongitude;
                
                \Log::info('Using coordinates for instant courier', [
                    'courier' => $courierCodes,
                    'origin' => "{$originLatitude}, {$originLongitude}",
                    'destination' => "{$destinationLatitude}, {$destinationLongitude}"
                ]);
            } else {
                // Untuk kurir reguler, gunakan postal code
                if (empty($originPostalCode)) {
                    \Log::error('Biteship API (Cost) Failed: Origin postal code is missing in .env for non-instant courier.');
                    return response()->json(['error' => 'Kode pos asal tidak diatur di konfigurasi.'], 500);
                }
                $requestBody['origin_postal_code'] = $originPostalCode;
                
                if (!empty($destinationPostalCode)) {
                    $requestBody['destination_postal_code'] = $destinationPostalCode;
                } elseif (!empty($destinationAreaId)) {
                    $requestBody['destination_area_id'] = $destinationAreaId;
                }
            }
            
            \Log::info('Biteship API (Cost) Request', [
                'endpoint' => $endpoint,
                'body' => $requestBody
            ]);
            
            $response = Http::withHeaders($this->getBiteshipHeaders())
                            ->post($endpoint, $requestBody);

            if (!$response->successful()) {
                \Log::error('Biteship API (Cost) Failed: ' . $response->status() . ' - ' . $response->body());
                
                $status = $response->status();
                $errorBody = $response->json();
                
                $rawMessage = $errorBody['error'] ?? $errorBody['message'] ?? 'Gagal mengambil biaya pengiriman.';
                
                // Handle specific error messages
                if (str_contains($rawMessage, 'No courier available')) {
                    $courierName = $this->getCourierDisplayName($courier);
                    $message = "Kurir {$courierName} tidak tersedia untuk lokasi ini. Silakan pilih kurir lain.";
                } elseif (str_contains($rawMessage, 'No sufficient balance')) {
                    $message = "Saldo API tidak cukup. Hubungi admin untuk top up saldo Biteship.";
                } else {
                    $message = $rawMessage;
                }
                
                return response()->json(['error' => $message], 500);
            }

            $data = $response->json();
            
            // Biteship mengembalikan pricing array
            $pricing = $data['pricing'] ?? [];
            
            // Transform ke format yang konsisten dengan frontend
            $results = collect($pricing)->map(function ($item) {
                return [
                    'courier_code' => $item['courier_code'] ?? '',
                    'courier_name' => $item['courier_name'] ?? '',
                    'service_code' => $item['courier_service_code'] ?? '',
                    'service' => $item['courier_service_name'] ?? '',
                    'name' => $item['courier_name'] ?? '',
                    'cost' => $item['price'] ?? 0,
                    'etd' => $item['duration'] ?? '-',
                    'description' => $item['description'] ?? '',
                ];
            })->values()->all();

            return response()->json($results);

        } catch (\Exception $e) {
            \Log::error('Biteship API Exception (Cost): ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Map courier code to Biteship format
     */
    private function mapCourierToBiteship($courier)
    {
        $mapping = [
            'jne' => 'jne',
            'sicepat' => 'sicepat',
            'jnt' => 'jnt',
            'gosend' => 'gojek', // Biteship menggunakan 'gojek' untuk GoSend
        ];
        
        return $mapping[$courier] ?? $courier;
    }

    /**
     * Get courier display name for user-friendly messages
     */
    private function getCourierDisplayName($courier)
    {
        $names = [
            'jne' => 'JNE',
            'sicepat' => 'SiCepat',
            'jnt' => 'J&T Express',
            'gosend' => 'GoSend',
        ];
        
        return $names[$courier] ?? strtoupper($courier);
    }

    /**
     * 🔎 NEW: Search Destination/Region by Keyword (Komerce Direct Search Method)
     */
    // app/Http/Controllers/CustomerController.php (di dalam searchDestination)

    public function searchDestination(Request $request)
    {
        $keyword = $request->keyword;
        \Log::info('BITESHIP DESTINATION SEARCH: Keyword received', ['keyword' => $keyword]);
        
        if (empty($keyword) || strlen($keyword) < 3) {
            return response()->json(['results' => []]);
        }

        try {
            $endpoint = config('services.biteship.base_url') . '/v1/maps/areas';
            
            $response = Http::withHeaders($this->getBiteshipHeaders())->get($endpoint, [
                'countries' => 'ID',
                'input' => $keyword,
                'type' => 'single'
            ]);

            if (!$response->successful()) {
                \Log::error('Biteship API (Search) Failed: ' . $response->status() . ' - ' . $response->body());
                
                if ($response->status() == 404) {
                    return response()->json(['results' => []]);
                }
                
                $errorBody = $response->json();
                $message = $errorBody['error'] ?? 'Gagal koneksi API Biteship (Status: ' . $response->status() . ').';
                return response()->json(['results' => [], 'error' => $message], 500);
            }

            $data = $response->json();
            $areas = $data['areas'] ?? []; 
            
            // Log response untuk debug koordinat
            if (!empty($areas)) {
                \Log::info('Biteship Maps API Response (first result)', [
                    'first_item' => $areas[0] ?? null
                ]);
            }
            
            // Transform ke format Select2
        $formattedResults = collect($areas)->map(function ($item) {
            // Biteship mengembalikan: id, name, country_name, postal_code, administrative_division_level_*, latitude, longitude
            return [
                'id' => $item['id'] ?? null,  // Biteship area_id
                'text' => $item['name'] ?? 'Alamat Tidak Dikenal', 
                'postal_code' => $item['postal_code'] ?? null,
                'province' => $item['administrative_division_level_1_name'] ?? null,
                'city' => $item['administrative_division_level_2_name'] ?? null,
                'district' => $item['administrative_division_level_3_name'] ?? null,
                // Tambah koordinat untuk instant couriers (GoSend, Grab)
                'latitude' => $item['latitude'] ?? null,
                'longitude' => $item['longitude'] ?? null,
            ];
        })->filter(function($item) {
            return $item['id'] !== null; 
        })->values()->all();

            return response()->json(['results' => $formattedResults]);

        } catch (\Exception $e) {
            \Log::error('Biteship API Exception (Search): ' . $e->getMessage());
            return response()->json(['results' => [], 'error' => 'Gagal koneksi ke server API. (Jaringan)'], 500);
        }
    }

    // --- FITUR UTAMA ---

    public function dashboard()
    {
        $user = auth()->user();
        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'delivered_orders' => $user->orders()->where('status', 'delivered')->count(),
        ];

        $recent_orders = $user->orders()->with('orderItems.menu')->latest()->take(3)->get();
        
        return view('customer.dashboard', compact('stats', 'recent_orders'));
    }

    public function menuIndex(Request $request)
    {
        $query = Menu::available();
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $menus = $query->paginate(12);
        $categories = Category::active()->ordered()->get();
        
        return view('customer.menu.index', compact('menus', 'categories'));
    }

    public function menuShow(Menu $menu)
    {
        return view('customer.menu.show', compact('menu'));
    }

    public function orderIndex(Request $request)
    {
        $query = Menu::available();
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $menus = $query->paginate(12)->withQueryString();
        $categories = \App\Models\Category::active()->ordered()->get();
        
        return view('customer.order.index', compact('menus', 'categories'));
    }

    // app/Http/Controllers/CustomerController.php

    public function orderCreate(Request $request)
    {
        // Validate cart data from session or request
        $cartItems = [];
        
        if ($request->has('cart_data')) {
            $cartItems = json_decode($request->cart_data, true);
        } elseif (session('cart_items')) {
            $cartItems = session('cart_items');
        }
        
        if (empty($cartItems)) {
            return redirect()->route('customer.order.index')
                ->with('error', 'Keranjang belanja kosong! Silakan pilih menu terlebih dahulu.');
        }

        // Validate all menu items exist and calculate total
        $menuIds = array_column($cartItems, 'id');
        $menus = Menu::whereIn('id', $menuIds)->where('is_available', true)->get();
        $menuData = $menus->keyBy('id');
        
        $validCartItems = [];
        $totalAmount = 0;
        $totalWeight = 0; 
        $itemValue = 0; // 💡 PERBAIKAN: Deklarasi dan Inisialisasi $itemValue
        
        foreach ($cartItems as $item) {
            if ($menuData->has($item['id'])) {
                $menu = $menuData->get($item['id']);
                
                // Hitung total berat
                $itemWeight = $menu->weight ?? 200; 
                $totalWeight += ($itemWeight * $item['quantity']);
                
                $validItem = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'weight' => $itemWeight, // Berat per item dalam gram
                    'total_weight' => $itemWeight * $item['quantity'], // Total berat item
                    'image' => $menu->image ? Storage::url($menu->image) : null,
                    'subtotal' => $menu->price * $item['quantity']
                ];
                $validCartItems[] = $validItem;
                $totalAmount += $validItem['subtotal'];
                $itemValue += $validItem['subtotal']; // 💡 PERBAIKAN: Hitung itemValue (Total nilai barang)
            }
        }
        
        if (empty($validCartItems)) {
            return redirect()->route('customer.order.index')
                ->with('error', 'Menu yang dipilih tidak tersedia!');
        }

        // Get payment methods - FIX: Pastikan ini ada dan aktif
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        // DEBUG: Cek apakah ada payment methods
        if ($paymentMethods->isEmpty()) {
            return redirect()->route('customer.order.index')
                ->with('error', 'Tidak ada metode pembayaran yang tersedia. Hubungi administrator.');
        }
        
        // Store cart in session for checkout process
        session(['checkout_cart' => $validCartItems]);
        
        // 💡 PERBAIKAN: Mengirim $itemValue, $totalWeight, dan $validCartItems ke view
        return view('customer.order.create', compact('validCartItems', 'totalAmount', 'paymentMethods', 'totalWeight', 'itemValue'));
    }

    public function orderStore(Request $request)
    {
        // 1. Validasi Input Lengkap dengan aturan yang lebih ketat
        $validated = $request->validate([
            'recipient_name' => [
                'required', 
                'string', 
                'min:3', 
                'max:100',
                'regex:/^[a-zA-Z\s\.]+$/' // Hanya huruf, spasi, dan titik
            ],
            'delivery_address' => [
                'required', 
                'string', 
                'min:15', 
                'max:500'
            ],
            'phone' => [
                'required', 
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/' // Format Indonesia: +62/62/0 diikuti 9-13 digit
            ],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            
            // Validasi Data Pengiriman
            'destination_id' => ['required', 'string'], // Destination ID Komerce
            'destination_name' => ['required', 'string'], // Nama kota/kecamatan
            'destination_province' => ['nullable', 'string'], // Provinsi
            'destination_postal_code' => ['nullable', 'string'], // Kode pos
            'destination_latitude' => ['nullable', 'numeric', 'between:-90,90'], // Latitude untuk GoSend
            'destination_longitude' => ['nullable', 'numeric', 'between:-180,180'], // Longitude untuk GoSend
            'courier' => ['required', 'string'],
            'shipping_service' => ['required', 'string'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'total_weight' => ['required', 'integer', 'min:1'],
        ], [
            // Custom error messages dalam Bahasa Indonesia
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_name.min' => 'Nama penerima minimal 3 karakter.',
            'recipient_name.max' => 'Nama penerima maksimal 100 karakter.',
            'recipient_name.regex' => 'Nama penerima hanya boleh berisi huruf, spasi, dan titik.',
            
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'delivery_address.min' => 'Alamat pengiriman minimal 15 karakter. Mohon lengkapi dengan detail.',
            'delivery_address.max' => 'Alamat pengiriman maksimal 500 karakter.',
            
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format: 08xx, 628xx, atau +628xx.',
            
            'destination_id.required' => 'Silakan pilih kota/kecamatan tujuan.',
            'courier.required' => 'Silakan pilih kurir pengiriman.',
            'shipping_service.required' => 'Silakan pilih layanan pengiriman.',
        ]);

        // 2. Cek Keranjang
        $cartItems = session('checkout_cart');
        
        if (empty($cartItems)) {
            return redirect()->route('customer.order.index')
                ->with('error', 'Keranjang belanja kosong!');
        }

        // 3. Ambil Metode Pembayaran (Error Check)
        try {
            $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
        } catch (\Exception $e) {
            \Log::error('Payment method not found', ['payment_method_id' => $validated['payment_method_id']]);
            return back()->with('error', 'Metode pembayaran tidak valid!');
        }

        // 4. Validasi Ulang Menu Availability
        $menuIds = array_column($cartItems, 'id');
        $menus = Menu::whereIn('id', $menuIds)->where('is_available', true)->get();
        $menuData = $menus->keyBy('id');
        
        foreach ($cartItems as $item) {
            if (!$menuData->has($item['id'])) {
                return back()->with('error', 'Menu "' . $item['name'] . '" tidak tersedia lagi!');
            }
        }

        try {
            $order = DB::transaction(function () use ($validated, $cartItems, $menuData, $paymentMethod) {
                // Generate order number
                $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
                
                // Calculate total amount (Subtotal Menu)
                $subtotalMenu = 0;
                foreach ($cartItems as $item) {
                    $menu = $menuData->get($item['id']);
                    $subtotalMenu += $menu->price * $item['quantity'];
                }
                
                // 💡 PERBAIKAN PENTING: Hitung Grand Total (Subtotal Menu + Ongkir)
                $grandTotal = $subtotalMenu + $validated['shipping_cost'];
                
                // 5. Create Order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => $orderNumber,
                    'total_amount' => $grandTotal, // <-- MENGGUNAKAN GRAND TOTAL (MENU + ONGKIR)
                    
                    'recipient_name' => $validated['recipient_name'], 
                    'delivery_address' => $validated['delivery_address'],
                    'phone' => $validated['phone'],
                    'notes' => $validated['notes'],
                    'status' => 'pending',
                    
                    // <-- DATA PENGIRIMAN DITAMBAHKAN DENGAN BENAR DI SINI -->
                    'city_id' => $validated['destination_id'], 
                    'destination_city' => $this->extractCityFromDestination($validated['destination_name']),
                    'destination_district' => $this->extractDistrictFromDestination($validated['destination_name']),
                    'destination_province' => $validated['destination_province'] ?? $this->extractProvinceFromDestination($validated['destination_name']),
                    'destination_postal_code' => $validated['destination_postal_code'] ?? null,
                    'destination_latitude' => $validated['destination_latitude'] ?? null, // Untuk GoSend
                    'destination_longitude' => $validated['destination_longitude'] ?? null, // Untuk GoSend
                    'courier' => $validated['courier'],
                    'shipping_service' => $validated['shipping_service'],
                    'shipping_cost' => $validated['shipping_cost'], // <-- ONGKIR DITAMBAH SEBAGAI FIELD TERPISAH
                    'total_weight' => $validated['total_weight'],
                ]);

                // 6. Create order items
                foreach ($cartItems as $item) {
                    $menu = $menuData->get($item['id']);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $item['quantity'],
                        'price' => $menu->price,
                        'notes' => null
                    ]);
                }

                // 7. Create payment record
               try {
                    Payment::create([
                        'order_id' => $order->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'amount' => $order->total_amount,
                        'status' => 'pending'
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create payment record', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                    throw new \Exception('Gagal membuat record pembayaran: ' . $e->getMessage());
                }

                return $order;
            });

            // ... (Clear session & Redirect) ...

            session()->forget('checkout_cart');

            // Redirect berdasarkan metode pembayaran
            if ($paymentMethod->type === 'midtrans') {
                return redirect()->route('customer.order.midtrans', $order);
            } else {
                return redirect()->route('customer.order.show', $order)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to create order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function orderMidtrans(Order $order)
    {
        try {
            $this->authorize('view', $order);
            
            // Check if order has expired (1 hour deadline)
            if ($order->created_at->addHour()->isPast()) {
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Waktu pembayaran sudah habis. Pesanan ini tidak dapat dibayar lagi. Silakan buat pesanan baru.');
            }
            
            \Log::info('=== MIDTRANS PAYMENT PAGE ACCESSED ===', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => auth()->id()
            ]);
            
            // Load relationships dengan error handling
            try {
                $order->load(['orderItems.menu', 'payment.paymentMethod', 'user']);
            } catch (\Exception $e) {
                \Log::error('Failed to load order relationships', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Gagal memuat data pesanan.');
            }
            
            // 💡 PERBAIKAN SINKRONISASI DATA: Refresh model untuk mendapatkan total_amount dan shipping_cost yang baru
            $order->refresh(); 

            // Pastikan ada payment record, jika tidak buat baru (KODE INI SUDAH DIHAPUS BERDASARKAN DEBUGGING SEBELUMNYA)
            
            // Validasi data order sebelum membuat transaksi
            if ($order->orderItems->isEmpty()) {
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Pesanan tidak memiliki item yang valid.');
            }
            
            if (!$order->user || !$order->user->email) {
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Data user tidak lengkap. Silakan update profil Anda.');
            }
            
            if (empty($order->delivery_address)) {
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Alamat pengiriman tidak boleh kosong.');
            }
            
            if (empty($order->phone)) {
                return redirect()->route('customer.order.show', $order)
                    ->with('error', 'Nomor telefon tidak boleh kosong.');
            }
            
            // Cek apakah snap token sudah ada dan masih valid
            if ($order->payment && $order->payment->snap_token) {
                \Log::info('Using existing snap token', [
                    'order_id' => $order->id,
                    'existing_token' => substr($order->payment->snap_token, 0, 20) . '...'
                ]);
                
                return view('customer.order.midtrans', [
                    'order' => $order,
                    'snapToken' => $order->payment->snap_token
                ]);
            }
            
            // Generate snap token dengan error handling yang lebih baik
            try {
                \Log::info('Generating new snap token', [
                    'order_id' => $order->id
                ]);
                
                // MidtransService akan menghitung line item (termasuk ongkir)
                $snapToken = $this->midtransService->createTransaction($order);
                
                if (empty($snapToken)) {
                    throw new \Exception('Snap token kosong dari service');
                }
                
                \Log::info('Snap token generated successfully', [
                    'order_id' => $order->id,
                    'token_preview' => substr($snapToken, 0, 20) . '...'
                ]);
                
                return view('customer.order.midtrans', [
                    'order' => $order,
                    'snapToken' => $snapToken
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Failed to create Midtrans transaction', [
                    'order_id' => $order->id,
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString()
                ]);
                
                // Berikan pesan error yang lebih spesifik berdasarkan jenis error
                $errorMessage = 'Gagal membuat transaksi pembayaran: ';
                
                if (str_contains($e->getMessage(), 'credentials')) {
                    $errorMessage .= 'Konfigurasi Midtrans belum benar. Hubungi administrator.';
                } elseif (str_contains($e->getMessage(), 'Undefined array key')) {
                    $errorMessage .= 'Ada kesalahan dalam data pesanan. Silakan buat pesanan ulang.';
                } elseif (str_contains($e->getMessage(), 'environment')) {
                    $errorMessage .= 'Konfigurasi environment tidak konsisten. Hubungi administrator.';
                } else {
                    $errorMessage .= $e->getMessage();
                }
                
                return redirect()->route('customer.order.show', $order)
                    ->with('error', $errorMessage);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error in orderMidtrans method', [
                'order_id' => $order->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.orders')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function midtransCallback(Request $request)
    {
        try {
            \Log::info('=== MIDTRANS CALLBACK RECEIVED ===', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Validate required fields
            $requiredFields = ['order_id', 'status_code', 'gross_amount', 'signature_key'];
            foreach ($requiredFields as $field) {
                if (!$request->has($field)) {
                    \Log::error('Missing required field in callback', ['field' => $field]);
                    return response()->json(['status' => 'error', 'message' => 'Missing required field: ' . $field], 400);
                }
            }

            // Validate signature
            $serverKey = config('services.midtrans.server_key');
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $signatureKey = $request->signature_key;
            
            $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            
            if (!hash_equals($hash, $signatureKey)) {
                \Log::error('Invalid signature in callback', [
                    'expected' => $hash,
                    'received' => $signatureKey,
                    'order_id' => $orderId
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }

            // Find payment record
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                \Log::error('Payment not found for callback', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            // Load order relationship
            $payment->load('order');
            $order = $payment->order;

            if (!$order) {
                \Log::error('Order not found for payment', ['payment_id' => $payment->id]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? null;
            $paymentType = $request->payment_type ?? null;
            $transactionId = $request->transaction_id ?? null;

            \Log::info('Processing transaction status', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Process different transaction statuses
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        // Transaction is challenged by FDS
                        $this->updatePaymentStatus($payment, $order, 'pending', $request->all(), $transactionId, $paymentType);
                        \Log::info('Payment challenged by FDS');
                    } elseif ($fraudStatus == 'accept') {
                        // Transaction is accepted
                        $this->updatePaymentStatus($payment, $order, 'confirmed', $request->all(), $transactionId, $paymentType);
                        \Log::info('Payment accepted after challenge');
                    }
                    break;

                case 'settlement':
                    // Transaction is settled (successful)
                    $this->updatePaymentStatus($payment, $order, 'confirmed', $request->all(), $transactionId, $paymentType);
                    \Log::info('Payment settled successfully');
                    break;

                case 'pending':
                    // Transaction is pending
                    $this->updatePaymentStatus($payment, $order, 'pending', $request->all(), $transactionId, $paymentType);
                    \Log::info('Payment is pending');
                    break;

                case 'deny':
                    // Transaction is denied
                    $this->updatePaymentStatus($payment, $order, 'failed', $request->all(), $transactionId, $paymentType);
                    \Log::info('Payment denied');
                    break;

                case 'expire':
                    // Transaction is expired
                    $this->updatePaymentStatus($payment, $order, 'expired', $request->all(), $transactionId, $paymentType);
                    \Log::info('Payment expired');
                    break;

                case 'cancel':
                    // Transaction is cancelled
                    $this->updatePaymentStatus($payment, $order, 'cancelled', $request->all(), $transactionId, $paymentType);
                    \Log::info('Payment cancelled');
                    break;

                default:
                    \Log::warning('Unknown transaction status', ['status' => $transactionStatus]);
                    $this->updatePaymentStatus($payment, $order, 'unknown', $request->all(), $transactionId, $paymentType);
            }

            return response()->json(['status' => 'OK']);

        } catch (\Exception $e) {
            \Log::error('Exception in Midtrans callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ✅ NEW: Helper method to update payment and order status
    private function updatePaymentStatus($payment, $order, $status, $callbackData, $transactionId = null, $paymentType = null)
    {
        try {
            $wasAlreadyConfirmed = $payment->status === 'confirmed';
            DB::transaction(function () use ($payment, $order, $status, $callbackData, $transactionId, $paymentType) {
                // Update payment record
                $paymentData = [
                    'status' => $status,
                    'midtrans_response' => $callbackData
                ];

                if ($transactionId) {
                    $paymentData['midtrans_transaction_id'] = $transactionId;
                }

                if ($paymentType) {
                    $paymentData['payment_type'] = $paymentType;
                }

                if ($status === 'confirmed') {
                    $paymentData['paid_at'] = now();
                    $paymentData['midtrans_paid_at'] = now();
                }

                $payment->update($paymentData);

                // Update order status based on payment status
                $orderStatus = $this->getOrderStatusFromPaymentStatus($status);
                if ($orderStatus && $order->status !== $orderStatus) {
                    $order->update(['status' => $orderStatus]);
                    
                    \Log::info('Order status updated', [
                        'order_id' => $order->id,
                        'old_status' => $order->getOriginal('status'),
                        'new_status' => $orderStatus,
                        'payment_status' => $status
                    ]);
                }
            });
            // ===== PENTING: PANGGIL WHATSAPP NOTIFIKASI DI SINI =====
            // Jika status BARU adalah confirmed, DAN status LAMA BUKAN confirmed
            if ($status === 'confirmed' && !$wasAlreadyConfirmed) {
                // Logika Akuntansi (sudah ada)
                \Log::info("Memanggil AccountingService untuk Order: {$order->order_number}");
                $this->accountingService->recordSale($order);
                
                // Refresh order data
                $order->refresh();
                $order->load('orderItems.menu'); // Load untuk Biteship
                
                // Debug log untuk melihat data order
                \Log::info('Order data untuk WA notifikasi', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'courier' => $order->courier,
                    'shipping_service' => $order->shipping_service,
                    'delivery_address' => $order->delivery_address
                ]);
                
                // 🔔 Panggil notifikasi WhatsApp Admin
                $this->midtransService->sendWhatsAppNotification($order, 'CONFIRMED');
                
                // 📦 DISABLED: Auto-create Biteship order
                // Sekarang admin harus manual trigger dari halaman order detail (untuk mendukung sistem PO)
                // Uncomment jika ingin auto-create lagi setelah pembayaran
                /*
                try {
                    \Log::info("Membuat order Biteship untuk: {$order->order_number}");
                    $biteshipResult = $this->biteshipService->createOrder($order);
                    
                    if ($biteshipResult && $biteshipResult['success']) {
                        \Log::info("Biteship order berhasil dibuat", [
                            'order_number' => $order->order_number,
                            'waybill_id' => $order->tracking_number
                        ]);
                    } elseif ($biteshipResult) {
                        \Log::warning("Biteship order gagal dibuat", [
                            'order_number' => $order->order_number,
                            'error' => $biteshipResult['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $biteshipException) {
                    // Log error tapi jangan gagalkan proses utama
                    \Log::error("Exception saat membuat Biteship order", [
                        'order_number' => $order->order_number,
                        'error' => $biteshipException->getMessage()
                    ]);
                }
                */
            }

        } catch (\Exception $e) {
            \Log::error('Failed to update payment status', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    // ✅ NEW: Map payment status to order status
    private function getOrderStatusFromPaymentStatus($paymentStatus)
    {
        $statusMap = [
            'confirmed' => 'confirmed',    // Payment confirmed -> Order confirmed
            'pending' => 'pending',        // Payment pending -> Order pending  
            'failed' => 'cancelled',       // Payment failed -> Order cancelled
            'expired' => 'cancelled',      // Payment expired -> Order cancelled
            'cancelled' => 'cancelled',    // Payment cancelled -> Order cancelled
        ];

        return $statusMap[$paymentStatus] ?? null;
    }

    // ✅ IMPROVED: Enhanced finish callback (redirect after successful payment)
    public function midtransFinish(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            \Log::info('Midtrans finish callback', [
                'order_id' => $orderId,
                'request_data' => $request->all()
            ]);

            if (!$orderId) {
                return redirect()->route('customer.dashboard')
                    ->with('error', 'ID pesanan tidak ditemukan');
            }

            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                \Log::warning('Payment not found in finish callback', ['order_id' => $orderId]);
                return redirect()->route('customer.dashboard')
                    ->with('error', 'Pesanan tidak ditemukan');
            }

            $order = $payment->order;

            // ✅ Try to get latest transaction status from Midtrans
            try {
                $transactionStatus = $this->midtransService->getTransactionStatus($orderId);
                
                if ($transactionStatus) {
                    \Log::info('Retrieved transaction status from Midtrans', [
                        'order_id' => $orderId,
                        'status' => $transactionStatus->transaction_status ?? 'unknown'
                    ]);

                    // Update status based on latest info from Midtrans
                    if (isset($transactionStatus->transaction_status)) {
                        if (in_array($transactionStatus->transaction_status, ['capture', 'settlement'])) {
                            $this->updatePaymentStatus($payment, $order, 'confirmed', (array)$transactionStatus, 
                                                     $transactionStatus->transaction_id ?? null, 
                                                     $transactionStatus->payment_type ?? null);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to get transaction status from Midtrans', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
            }

            // Reload payment to get latest status
            $payment->refresh();
            $order->refresh();

            $message = 'Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.';
            $messageType = 'success';

            // Customize message based on current status
            if ($payment->status === 'confirmed') {
                $message = 'Pembayaran berhasil! Pesanan Anda telah dikonfirmasi dan sedang diproses.';
            } elseif ($payment->status === 'pending') {
                $message = 'Pembayaran sedang diproses. Kami akan mengupdate status pesanan Anda segera.';
                $messageType = 'info';
            }

            return redirect()->route('customer.order.show', $order)
                ->with($messageType, $message);

        } catch (\Exception $e) {
            \Log::error('Error in midtrans finish callback', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }

    // ✅ IMPROVED: Enhanced unfinish callback
    public function midtransUnfinish(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            \Log::info('Midtrans unfinish callback', [
                'order_id' => $orderId,
                'request_data' => $request->all()
            ]);

            if (!$orderId) {
                return redirect()->route('customer.dashboard')
                    ->with('warning', 'Pembayaran belum selesai');
            }

            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                return redirect()->route('customer.dashboard')
                    ->with('warning', 'Pesanan tidak ditemukan');
            }

            $order = $payment->order;

            return redirect()->route('customer.order.show', $order)
                ->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda atau coba lagi.');

        } catch (\Exception $e) {
            \Log::error('Error in midtrans unfinish callback', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('warning', 'Pembayaran belum selesai');
        }
    }

    // ✅ IMPROVED: Enhanced error callback
    public function midtransError(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            \Log::info('Midtrans error callback', [
                'order_id' => $orderId,
                'request_data' => $request->all()
            ]);

            if (!$orderId) {
                return redirect()->route('customer.order.index')
                    ->with('error', 'Pembayaran gagal');
            }

            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                return redirect()->route('customer.order.index')
                    ->with('error', 'Pembayaran gagal. Pesanan tidak ditemukan.');
            }

            $order = $payment->order;

            // Update payment status to failed
            $this->updatePaymentStatus($payment, $order, 'failed', $request->all());

            return redirect()->route('customer.order.show', $order)
                ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi atau hubungi customer service.');

        } catch (\Exception $e) {
            \Log::error('Error in midtrans error callback', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('customer.order.index')
                ->with('error', 'Pembayaran gagal');
        }
    }

    // ✅ NEW: Manual status check method (for admin or debugging)
    public function checkPaymentStatus(Order $order)
    {
        try {
            $this->authorize('view', $order);
            
            if (!$order->payment || !$order->payment->midtrans_order_id) {
                return redirect()->back()
                    ->with('error', 'Pesanan ini tidak memiliki data pembayaran Midtrans');
            }

            $midtransOrderId = $order->payment->midtrans_order_id;
            
            // Get status from Midtrans
            $transactionStatus = $this->midtransService->getTransactionStatus($midtransOrderId);
            
            if (!$transactionStatus) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat mengambil status dari Midtrans');
            }

            \Log::info('Manual status check result', [
                'order_id' => $order->id,
                'midtrans_order_id' => $midtransOrderId,
                'transaction_status' => $transactionStatus->transaction_status ?? 'unknown'
            ]);

            // Update local status based on Midtrans response
            if (isset($transactionStatus->transaction_status)) {
                $status = $transactionStatus->transaction_status;
                
                if (in_array($status, ['capture', 'settlement'])) {
                    $this->updatePaymentStatus($order->payment, $order, 'confirmed', (array)$transactionStatus,
                                             $transactionStatus->transaction_id ?? null,
                                             $transactionStatus->payment_type ?? null);
                    $message = 'Status pembayaran berhasil diperbarui: Terkonfirmasi';
                    $type = 'success';
                } elseif ($status === 'pending') {
                    $this->updatePaymentStatus($order->payment, $order, 'pending', (array)$transactionStatus);
                    $message = 'Status pembayaran: Pending';
                    $type = 'info';
                } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                    $this->updatePaymentStatus($order->payment, $order, 'failed', (array)$transactionStatus);
                    $message = 'Status pembayaran: Gagal/Dibatalkan';
                    $type = 'error';
                } else {
                    $message = 'Status pembayaran: ' . $status;
                    $type = 'info';
                }
            } else {
                $message = 'Status tidak dapat ditentukan dari respons Midtrans';
                $type = 'warning';
            }

            return redirect()->back()->with($type, $message);

        } catch (\Exception $e) {
            \Log::error('Error checking payment status', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal memeriksa status pembayaran: ' . $e->getMessage());
        }
    }
    
    public function orders(Request $request)
    {
        $query = auth()->user()->orders()->with('orderItems.menu');

        // Search filter - by order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Period filter
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
        
        // Custom date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        
        return view('customer.order.history', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['orderItems.menu', 'payment.paymentMethod']);
        return view('customer.order.show', compact('order'));
    }

    public function orderReorder(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load('orderItems.menu');
        
        // Convert order items back to cart format
        $cartItems = [];
        foreach ($order->orderItems as $item) {
            if ($item->menu->is_available) {
                $cartItems[] = [
                    'id' => $item->menu->id,
                    'name' => $item->menu->name,
                    'price' => $item->menu->price,
                    'quantity' => $item->quantity,
                    'image' => $item->menu->image ? Storage::url($item->menu->image) : null
                ];
            }
        }
        
        if (empty($cartItems)) {
            return redirect()->route('customer.order.index')
                ->with('warning', 'Beberapa menu dari pesanan sebelumnya tidak tersedia lagi.');
        }
        
        // Store in session and redirect to checkout
        session(['checkout_cart' => $cartItems]);
        
        return redirect()->route('customer.order.create')
            ->with('reorder_data', [
                'delivery_address' => $order->delivery_address,
                'phone' => $order->phone,
                'notes' => $order->notes
            ])
            ->with('success', 'Item berhasil ditambahkan ke keranjang!');
    }

    /**
     * Customer confirms that the order has been delivered/received
     */
    public function confirmDelivery(Order $order)
    {
        $this->authorize('view', $order);
        
        // Only allow confirmation if order is in 'ready' status (Dalam Perjalanan)
        if ($order->status !== 'ready') {
            return back()->with('error', 'Pesanan belum dalam status "Dalam Perjalanan".');
        }
        
        // Update status to delivered
        $order->update(['status' => 'delivered']);
        
        return back()->with('success', 'Terima kasih! Pesanan Anda telah dikonfirmasi sampai. Selamat menikmati! 🎉');
    }

    /**
     * Get order tracking information from Biteship
     */
    public function orderTracking(Order $order)
    {
        $this->authorize('view', $order);
        
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
                                'note' => 'Kurir instan ('. strtoupper($order->courier) .') akan menjemput pesanan Anda segera.',
                                'updated_at' => now()->toIso8601String()
                            ],
                            [
                                'note' => 'Tracking real-time akan tersedia setelah kurir mengambil paket. Pantau notifikasi dari aplikasi untuk update.',
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
        $tracking = $this->biteshipService->getTracking($trackingId, $order->courier);
        
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

    // ==================== CHAT METHODS ====================

    /**
     * Display the chat interface
     */
    public function chatIndex(Request $request)
    {
        $user = auth()->user();
        
        // Get or create conversation for this user
        $conversation = \App\Models\Conversation::getOrCreateForUser($user->id);
        
        // Get all messages in this conversation
        $rawMessages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        
        // Transform messages for JSON (avoid closure in blade)
        $messages = $rawMessages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'sender_type' => $msg->sender_type,
                'created_at' => $msg->created_at->format('H:i'),
                'is_read' => $msg->is_read,
            ];
        })->values();
        
        $lastMessageId = $rawMessages->last()->id ?? 0;
        
        // Mark messages as read by user
        $conversation->messages()
            ->where('sender_type', '!=', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'message_status' => 'read']);
        
        // Clear unread flag for user
        $conversation->update(['has_unread_user' => false]);
        
        // Get chatbot quick replies
        $chatbotService = new \App\Services\ChatbotService();
        $quickReplies = $chatbotService->getQuickReplies();

        // Get prefill message from URL (from order inquiry)
        $prefillMessage = $request->input('prefill', '');
        $orderContext = $request->input('order_id', null);

        return view('customer.chat.index', compact('conversation', 'messages', 'quickReplies', 'lastMessageId', 'prefillMessage', 'orderContext'));
    }

    /**
     * Send a new chat message
     */
    public function chatSend(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'skip_bot' => 'nullable|boolean', // Skip chatbot for order inquiries
        ]);

        $user = auth()->user();
        
        // Get or create conversation
        $conversation = \App\Models\Conversation::getOrCreateForUser($user->id);
        
        // Detect if this is an order inquiry (contains order pattern)
        $isOrderInquiry = preg_match('/Order #\d+|Pesanan #\d+|bertanya tentang pesanan/i', $validated['message']);
        $skipBot = $request->input('skip_bot', false) || $isOrderInquiry;
        
        // Create user message
        $userMessage = Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'sender_type' => Message::SENDER_USER,
            'sender_id' => $user->id,
            'subject' => $isOrderInquiry ? 'Pertanyaan Pesanan' : 'Chat Pelanggan',
            'message' => $validated['message'],
            'message_status' => Message::STATUS_SENT,
            'is_read' => false,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'has_unread_admin' => true,
        ]);

        $botMessage = null;

        // Only process chatbot if NOT an order inquiry
        if (!$skipBot) {
            $chatbotService = new \App\Services\ChatbotService();
            $botMessage = $chatbotService->processMessage($validated['message'], $conversation);
        } else {
            // Send notification message that admin will respond
            $botMessage = Message::create([
                'user_id' => $user->id,
                'conversation_id' => $conversation->id,
                'sender_type' => Message::SENDER_CHATBOT,
                'sender_id' => null,
                'subject' => 'Auto Reply',
                'message' => "✅ Pertanyaan Anda tentang pesanan telah diterima!\n\nAdmin kami akan segera membalas pesan Anda. Mohon ditunggu ya 🙏",
                'message_status' => Message::STATUS_DELIVERED,
                'is_read' => false,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'user_message' => $userMessage,
            'bot_message' => $botMessage,
        ]);
    }

    /**
     * Fetch new messages (for polling)
     */
    public function chatFetch(Request $request)
    {
        $user = auth()->user();
        $lastId = $request->input('last_id', 0);
        
        $conversation = \App\Models\Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            return response()->json(['messages' => [], 'last_id' => 0]);
        }

        // Get messages after last_id
        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_type', '!=', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'message_status' => 'read']);
        
        $conversation->update(['has_unread_user' => false]);

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
     * Clear chat history
     */
    public function chatClear()
    {
        $user = auth()->user();
        
        // Close current conversation
        \App\Models\Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->update(['status' => 'closed']);

        return response()->json(['status' => 'success', 'message' => 'Riwayat chat berhasil dihapus.']);
    }

    // ==================== LEGACY MESSAGE METHODS ====================
    // Keep for backward compatibility

    public function messageIndex(Request $request)
    {
        // Check if coming from order inquiry
        if ($request->has('order_id')) {
            $order = Order::with('orderItems.menu')->find($request->order_id);
            
            if ($order && $order->user_id === auth()->id()) {
                // Pre-fill message with order context
                $orderItems = $order->orderItems->map(fn($item) => $item->menu->name . ' x' . $item->quantity)->implode(', ');
                $prefillMessage = "Halo Admin, saya ingin bertanya tentang pesanan saya:\n\n" .
                    "📦 Order #" . ($order->order_number ?? $order->id) . "\n" .
                    "📅 Tanggal: " . $order->created_at->format('d/m/Y H:i') . "\n" .
                    "🍽️ Item: " . $orderItems . "\n" .
                    "💰 Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n" .
                    "📊 Status: " . ucfirst($order->status) . "\n\n" .
                    "Pertanyaan saya: ";
                
                return redirect()->route('customer.chat.index', ['prefill' => $prefillMessage, 'order_id' => $order->id]);
            }
        }
        
        return redirect()->route('customer.chat.index');
    }

    public function messageStore(Request $request)
    {
        return $this->chatSend($request);
    }

    public function clearChat()
    {
        return $this->chatClear();
    }

    public function messageJson()
    {
        return $this->chatFetch(request());
    }

    public function messageShow(Message $message)
    {
        $this->authorize('view', $message);
        return view('customer.message.show', compact('message'));
    }

    /**
     * Extract city name from destination string (format: "Kecamatan, Kota/Kabupaten")
     */
    private function extractCityFromDestination(string $destination): string
    {
        $parts = explode(',', $destination);
        if (count($parts) >= 2) {
            return trim($parts[1]); // Kota/Kabupaten
        }
        return trim($destination);
    }

    /**
     * Extract district name from destination string (format: "Kecamatan, Kota/Kabupaten, Provinsi")
     */
    private function extractDistrictFromDestination(string $destination): string
    {
        $parts = explode(',', $destination);
        if (count($parts) >= 1) {
            return trim($parts[0]); // Kecamatan
        }
        return '';
    }

    /**
     * Extract province name from destination string (format: "Kecamatan, Kota/Kabupaten, Provinsi")
     */
    private function extractProvinceFromDestination(string $destination): string
    {
        $parts = explode(',', $destination);
        if (count($parts) >= 3) {
            return trim($parts[2]); // Provinsi
        }
        return '';
    }
}