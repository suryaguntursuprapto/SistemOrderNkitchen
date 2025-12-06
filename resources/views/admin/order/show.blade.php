@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.order.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $order->order_number }}</p>
                </div>
            </div>
            <!-- Print Label Button -->
            <a href="{{ route('admin.order.shipping-label', $order) }}" 
               style="background: linear-gradient(to right, #22c55e, #059669);"
               class="flex items-center text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Label
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Order Info -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Pesanan</h3>
                    <form action="{{ route('admin.order.update', $order) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('PUT')
                        <select name="status" onchange="this.form.submit()"
                                class="text-sm border-0 rounded-full px-3 py-1 font-medium focus:ring-2 focus:ring-orange-500
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                                @elseif($order->status == 'ready') bg-green-100 text-green-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Detail Pesanan</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nomor Pesanan:</span>
                                <span class="text-gray-900">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="text-gray-900">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="text-gray-900 font-semibold">{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Pelanggan</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="text-gray-900">{{ $order->recipient_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="text-gray-900">{{ $order->user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Telepon:</span>
                                <span class="text-gray-900">{{ $order->phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-3">Alamat Pengiriman</h4>
                    <p class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $order->delivery_address }}</p>
                </div>
                
                <!-- Shipping Info & Tracking Number -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        🚚 Informasi Pengiriman
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="text-sm">
                            <span class="text-gray-600">Kurir:</span>
                            <span class="text-gray-900 font-medium ml-2">{{ strtoupper($order->courier ?? 'Belum dipilih') }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-600">Layanan:</span>
                            <span class="text-gray-900 font-medium ml-2">{{ $order->shipping_service ?? '-' }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-600">Ongkir:</span>
                            <span class="text-gray-900 font-medium ml-2">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-600">Berat Total:</span>
                            <span class="text-gray-900 font-medium ml-2">{{ number_format($order->total_weight ?? 0, 0, ',', '.') }} gram</span>
                        </div>
                    </div>
                    
                    <!-- Tracking Number Form -->
                    <div class="border-t border-blue-200 pt-4 mt-4">
                        <form action="{{ route('admin.order.update', $order) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $order->status }}">
                            <div class="flex-1">
                                <label class="block text-sm text-gray-700 mb-1 font-medium">📦 No. Resi / Tracking Number</label>
                                <input type="text" 
                                       name="tracking_number" 
                                       value="{{ $order->tracking_number }}"
                                       placeholder="Masukkan nomor resi pengiriman..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div class="sm:self-end">
                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors shadow-md">
                                    💾 Simpan Resi
                                </button>
                            </div>
                        </form>
                        @if($order->tracking_number)
                        <div class="mt-3 p-3 bg-green-100 border border-green-300 rounded-lg">
                            <p class="text-sm text-green-800">
                                ✅ No. Resi tersimpan: <strong class="font-mono">{{ $order->tracking_number }}</strong>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($order->notes)
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan</h4>
                        <p class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Midtrans Payment Information -->
        @if($order->payment)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Pembayaran</h3>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->payment->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($order->payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment->status === 'failed') bg-red-100 text-red-800
                            @elseif($order->payment->status === 'expired') bg-gray-100 text-gray-800
                            @elseif($order->payment->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($order->payment->status === 'confirmed')
                                ✅ Terkonfirmasi
                            @elseif($order->payment->status === 'pending')
                                ⏳ Pending
                            @elseif($order->payment->status === 'failed')
                                ❌ Gagal
                            @elseif($order->payment->status === 'expired')
                                ⏰ Kedaluwarsa
                            @elseif($order->payment->status === 'cancelled')
                                🚫 Dibatalkan
                            @else
                                ❓ {{ ucfirst($order->payment->status) }}
                            @endif
                        </span>
                        
                        @if($order->payment->midtrans_order_id)
                            <a href="{{ route('customer.orders', $order) }}" 
                               class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded"
                               title="Cek Status Terbaru dari Midtrans">
                                🔄 Refresh Status
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method & Status -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Metode Pembayaran</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Provider:</span>
                                <span class="text-gray-900">
                                    {{-- ✅ FIX: Add null check for paymentMethod --}}
                                    @if($order->payment->paymentMethod)
                                        {{ $order->payment->paymentMethod->name }}
                                    @else
                                        <span class="text-red-500 text-xs">Payment Method Not Found</span>
                                    @endif
                                </span>
                            </div>
                            
                            {{-- ✅ FIX: Add null check for paymentMethod type --}}
                            @if($order->payment->paymentMethod && $order->payment->paymentMethod->type)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipe:</span>
                                    <span class="text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment->paymentMethod->type) }}</span>
                                </div>
                            @endif
                            
                            @if($order->payment->payment_type)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jenis Pembayaran:</span>
                                    <span class="text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment->payment_type) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah:</span>
                                <span class="text-gray-900 font-semibold">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                            </div>
                            @if($order->payment->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibayar pada:</span>
                                    <span class="text-gray-900">{{ $order->payment->paid_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Midtrans Details -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Detail Midtrans</h4>
                        <div class="space-y-2 text-sm">
                            @if($order->payment->midtrans_order_id)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Midtrans Order ID:</span>
                                    <span class="text-gray-900 font-mono text-xs">{{ $order->payment->midtrans_order_id }}</span>
                                </div>
                            @endif
                            
                            @if($order->payment->midtrans_transaction_id)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transaction ID:</span>
                                    <span class="text-gray-900 font-mono text-xs">{{ $order->payment->midtrans_transaction_id }}</span>
                                </div>
                            @endif
                            
                            @if($order->payment->snap_token)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Snap Token:</span>
                                    <span class="text-gray-900 font-mono text-xs">{{ substr($order->payment->snap_token, 0, 20) }}...</span>
                                </div>
                            @endif
                            
                            @if($order->payment->midtrans_paid_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Konfirmasi Midtrans:</span>
                                    <span class="text-gray-900">{{ $order->payment->midtrans_paid_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method Issues Warning -->
                @if(!$order->payment->paymentMethod)
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">Payment Method Missing</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Payment method record tidak ditemukan. Payment Method ID: {{ $order->payment->payment_method_id ?? 'NULL' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Midtrans Response Details (jika ada) -->
                @if($order->payment->midtrans_response)
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-900">Response Midtrans</h4>
                            <button onclick="toggleMidtransResponse()" 
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                    id="toggle-response-btn">
                                Lihat Detail
                            </button>
                        </div>
                        <div id="midtrans-response" class="hidden">
                            <div class="bg-gray-50 rounded p-3 max-h-64 overflow-y-auto">
                                <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($order->payment->midtrans_response, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Payment Timeline -->
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-3">Timeline Pembayaran</h4>
                    <div class="space-y-3">
                        <!-- Payment Created -->
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">Payment record dibuat</p>
                                <p class="text-gray-500">{{ $order->payment->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->payment->midtrans_paid_at)
                            <!-- Payment Confirmed by Midtrans -->
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Dikonfirmasi oleh Midtrans</p>
                                    <p class="text-gray-500">{{ $order->payment->midtrans_paid_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->payment->paid_at && $order->payment->paid_at != $order->payment->midtrans_paid_at)
                            <!-- Payment Marked as Paid -->
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Ditandai sebagai lunas</p>
                                    <p class="text-gray-500">{{ $order->payment->paid_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->payment->status === 'failed' || $order->payment->status === 'expired' || $order->payment->status === 'cancelled')
                            <!-- Payment Failed/Expired/Cancelled -->
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">
                                        @if($order->payment->status === 'failed') Pembayaran gagal
                                        @elseif($order->payment->status === 'expired') Pembayaran kedaluwarsa
                                        @elseif($order->payment->status === 'cancelled') Pembayaran dibatalkan
                                        @endif
                                    </p>
                                    <p class="text-gray-500">{{ $order->payment->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- No Payment Info -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Pembayaran</h3>
            </div>
            <div class="px-6 py-4">
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Pembayaran</h3>
                    <p class="text-gray-600">Pesanan ini belum memiliki record pembayaran</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Item Pesanan</h3>
            </div>
            
            <div class="px-6 py-4">
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-4">
                                @if($item->menu->image)
                                    <img src="{{ Storage::url($item->menu->image) }}" alt="{{ $item->menu->name }}" 
                                         class="h-16 w-16 object-cover rounded">
                                @else
                                    <div class="h-16 w-16 bg-orange-100 rounded flex items-center justify-center">
                                        <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item->menu->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                    @if($item->notes)
                                        <p class="text-xs text-gray-500">Catatan: {{ $item->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ $item->formatted_subtotal }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-orange-600">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMidtransResponse() {
    const responseDiv = document.getElementById('midtrans-response');
    const toggleBtn = document.getElementById('toggle-response-btn');
    
    if (responseDiv.classList.contains('hidden')) {
        responseDiv.classList.remove('hidden');
        toggleBtn.textContent = 'Sembunyikan';
    } else {
        responseDiv.classList.add('hidden');
        toggleBtn.textContent = 'Lihat Detail';
    }
}
</script>
@endsection