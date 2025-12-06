@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer.order.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $order->order_number }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Order Status -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Status Pesanan</h3>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center justify-center mb-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                        @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
                
                <div class="text-center text-sm text-gray-600">
                    @if($order->status == 'pending')
                        <p>Pesanan Anda sedang menunggu konfirmasi dari kami.</p>
                    @elseif($order->status == 'confirmed')
                        <p>Pesanan Anda telah dikonfirmasi dan akan segera disiapkan.</p>
                    @elseif($order->status == 'preparing')
                        <p>Pesanan Anda sedang disiapkan dengan sepenuh hati.</p>
                    @elseif($order->status == 'ready')
                        <p>Pesanan Anda sedang dalam perjalanan! Mohon konfirmasi jika sudah sampai.</p>
                        
                        {{-- Tombol Konfirmasi Pesanan Sampai --}}
                        <form action="{{ route('customer.order.confirm-delivery', $order) }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah pesanan sudah sampai dan Anda sudah menerimanya?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pesanan Telah Sampai
                            </button>
                        </form>
                    @elseif($order->status == 'delivered')
                        <p>Pesanan Anda telah selesai. Terima kasih!</p>
                    @else
                        <p>Pesanan telah dibatalkan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->formatted_total }}</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-4">
                                @if($item->menu->image)
                                    <img src="{{ Storage::url($item->menu->image) }}" alt="{{ $item->menu->name }}" 
                                         class="h-16 w-16 object-cover rounded-lg">
                                @else
                                    <div class="h-16 w-16 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item->menu->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->quantity }}x {{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</p>
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
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Pengiriman</h3>
            </div>
            
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Alamat Pengiriman</p>
                            <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Nomor Telepon</p>
                            <p class="text-sm text-gray-600">{{ $order->phone }}</p>
                        </div>
                    </div>

                    @if($order->courier)
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Kurir Pengiriman</p>
                                <p class="text-sm text-gray-600">{{ strtoupper($order->courier) }} {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }}</p>
                            </div>
                        </div>
                    @endif

                    @if($order->tracking_number)
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nomor Resi</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-mono bg-green-50 text-green-700 px-2 py-1 rounded">{{ $order->tracking_number }}</p>
                                    <button onclick="copyToClipboard('{{ $order->tracking_number }}')" class="text-gray-400 hover:text-gray-600" title="Salin Nomor Resi">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Gunakan nomor resi ini untuk melacak paket Anda</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->notes)
                        <div class="flex items-start space-x-3">
                            <svg class="h-5 w-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Catatan</p>
                                <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($order->status == 'delivered')
            <div class="text-center">
                <div class="bg-green-50 rounded-lg p-6">
                    <svg class="mx-auto h-12 w-12 text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-green-900 mb-2">Pesanan Selesai!</h3>
                    <p class="text-sm text-green-700">Terima kasih telah memesan di N-Kitchen. Semoga Anda puas dengan pempek kami!</p>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor resi berhasil disalin: ' + text);
    }).catch(err => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Nomor resi berhasil disalin: ' + text);
    });
}
</script>
@endpush
@endsection