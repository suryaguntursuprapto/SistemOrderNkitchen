@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
        50% { box-shadow: 0 0 20px 5px rgba(249, 115, 22, 0.2); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
    .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
    .animate-slideInRight { animation: slideInRight 0.5s ease-out forwards; }
    .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.15); }
    .item-hover { transition: all 0.2s ease; }
    .item-hover:hover { background: linear-gradient(90deg, rgba(249,115,22,0.05) 0%, transparent 100%); transform: translateX(4px); }
    .gradient-border { position: relative; }
    .gradient-border::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #f97316, #ef4444, #f97316); background-size: 200% 100%; animation: shimmer 3s linear infinite; }
    .btn-shine { position: relative; overflow: hidden; }
    .btn-shine::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(60deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%); transform: rotate(45deg); animation: shimmer 3s linear infinite; }
</style>

<div class="min-h-screen py-6 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Back Button & Header --}}
        <div class="mb-6 opacity-0 animate-fadeInUp">
            <a href="{{ route('customer.orders') }}" 
               class="inline-flex items-center text-gray-600 hover:text-orange-600 transition-all duration-300 mb-4 group">
                <div class="w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center mr-2 group-hover:-translate-x-1 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </div>
                <span class="font-medium">Kembali ke Riwayat</span>
            </a>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Order #{{ $order->order_number }}
                    </h1>
                    <p class="text-gray-500 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                
                <a href="{{ route('customer.message.index', ['order_id' => $order->id]) }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Tanya Pesanan
                </a>
            </div>
        </div>

        @php
            $subtotalMenu = $order->total_amount - $order->shipping_cost;
            $isExpired = $order->status === 'pending' && $order->created_at->addHour()->isPast();
            $deadline = $order->created_at->addHour();
            $remaining = now()->diffInSeconds($deadline, false);
            
            $statusConfig = [
                'pending' => ['bg' => 'from-amber-400 to-orange-500', 'light' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => '⏳', 'label' => 'Menunggu Pembayaran', 'glow' => 'shadow-amber-200'],
                'confirmed' => ['bg' => 'from-blue-400 to-indigo-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => '✅', 'label' => 'Dikonfirmasi', 'glow' => 'shadow-blue-200'],
                'processing' => ['bg' => 'from-cyan-400 to-teal-500', 'light' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'icon' => '👨‍🍳', 'label' => 'Sedang Disiapkan', 'glow' => 'shadow-cyan-200'],
                'ready' => ['bg' => 'from-indigo-400 to-purple-500', 'light' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'icon' => '📦', 'label' => 'Dikirim', 'glow' => 'shadow-indigo-200'],
                'shipped' => ['bg' => 'from-violet-400 to-purple-500', 'light' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200', 'icon' => '🚚', 'label' => 'Dalam Pengiriman', 'glow' => 'shadow-violet-200'],
                'delivered' => ['bg' => 'from-emerald-400 to-green-500', 'light' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => '🎉', 'label' => 'Selesai', 'glow' => 'shadow-emerald-200'],
                'cancelled' => ['bg' => 'from-red-400 to-rose-500', 'light' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => '❌', 'label' => 'Dibatalkan', 'glow' => 'shadow-red-200'],
                'expired' => ['bg' => 'from-gray-400 to-gray-500', 'light' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'icon' => '⏰', 'label' => 'Waktu Habis - Dibatalkan', 'glow' => 'shadow-gray-200'],
            ];
            
            $displayStatus = ($order->status === 'pending' && $isExpired) ? 'expired' : $order->status;
            $status = $statusConfig[$displayStatus] ?? $statusConfig['pending'];
        @endphp

        {{-- Status Card --}}
        <div class="bg-white rounded-3xl shadow-xl border {{ $status['border'] }} overflow-hidden mb-6 opacity-0 animate-fadeInUp delay-100 card-hover gradient-border">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-gradient-to-br {{ $status['bg'] }} rounded-2xl flex items-center justify-center text-3xl shadow-lg {{ $status['glow'] }} transform hover:scale-110 transition-transform">
                            {{ $status['icon'] }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold {{ $status['text'] }}">{{ $status['label'] }}</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                @if($order->status === 'pending' && !$isExpired)
                                    Segera selesaikan pembayaran Anda
                                @elseif($order->status === 'pending' && $isExpired)
                                    Pesanan dibatalkan karena melebihi batas waktu
                                @elseif($order->status === 'cancelled')
                                    Pesanan telah dibatalkan
                                @elseif($order->status === 'processing')
                                    Pesanan sedang dimasak dengan penuh cinta ❤️
                                @elseif($order->status === 'shipped' || $order->status === 'ready')
                                    Pesanan akan segera sampai di tujuan! 🚀
                                @elseif($order->status === 'delivered')
                                    Terima kasih telah berbelanja! 🙏
                                @else
                                    Terima kasih telah memesan
                                @endif
                            </p>
                            
                            {{-- Countdown Timer --}}
                            @if($order->status === 'pending' && !$isExpired)
                                <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-50 to-amber-50 rounded-full border border-orange-200" 
                                     x-data="{ timeLeft: {{ max(0, $remaining) }} }" 
                                     x-init="setInterval(() => { if(timeLeft > 0) timeLeft--; }, 1000)">
                                    <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                                    <span class="text-sm font-semibold" :class="timeLeft <= 600 ? 'text-red-600' : 'text-orange-600'">
                                        Sisa waktu: 
                                        <span x-text="Math.floor(timeLeft/60).toString().padStart(2,'0') + ':' + (timeLeft%60).toString().padStart(2,'0')" class="font-mono"></span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-3">
                        @if($order->status === 'pending' && !$isExpired && $order->payment && $order->payment->paymentMethod)
                            <a href="{{ route('customer.order.midtrans', $order) }}" 
                               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 btn-shine animate-pulse-glow"
                               style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Bayar Sekarang
                            </a>
                        @elseif($order->status === 'pending' && $isExpired)
                            <span class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Expired
                            </span>
                            <a href="{{ route('customer.order.index') }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Pesan Ulang
                            </a>
                        @elseif($order->status === 'ready')
                            <div x-data="{ showModal: false }">
                                <button @click="showModal = true" type="button" class="inline-flex items-center px-6 py-3 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Pesanan Diterima
                                </button>
                                
                                <!-- Modal Popup - Teleported to body -->
                                <template x-teleport="body">
                                    <div x-show="showModal" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4" 
                                         style="background: rgba(0,0,0,0.5);"
                                         @click.self="showModal = false"
                                         @keydown.escape.window="showModal = false">
                                        <div x-show="showModal"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 scale-90"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-90"
                                             class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4">
                                            <div class="text-center">
                                                <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Penerimaan</h3>
                                                <p class="text-gray-600 mb-6">Apakah pesanan sudah diterima dengan baik? Pastikan semua item sudah sesuai.</p>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <button @click="showModal = false" type="button" class="flex-1 px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                                                        Batal
                                                    </button>
                                                    <form action="{{ route('customer.order.confirm-delivery', $order) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full px-5 py-3 text-white font-semibold rounded-xl transition-all hover:shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                            Ya, Diterima
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @elseif(in_array($order->status, ['delivered', 'confirmed']))
                            <form action="{{ route('customer.order.reorder', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 font-medium rounded-xl transition-all hover:shadow-md hover:-translate-y-0.5 border border-orange-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Pesan Lagi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Progress Steps --}}
            @if(!in_array($order->status, ['cancelled']) && !$isExpired)
            <div class="px-6 sm:px-8 pb-6 bg-gradient-to-b from-transparent to-gray-50/50">
                <div class="flex items-center justify-between relative">
                    <div class="absolute top-5 left-0 right-0 h-1.5 bg-gray-200 rounded-full"></div>
                    <div class="absolute top-5 left-0 h-1.5 rounded-full transition-all duration-1000 ease-out"
                         style="width: {{ $order->status === 'pending' ? '0%' : ($order->status === 'confirmed' ? '25%' : ($order->status === 'processing' ? '50%' : ($order->status === 'shipped' || $order->status === 'ready' ? '75%' : '100%'))) }}; background: linear-gradient(90deg, #f97316, #ef4444);"></div>
                    
                    @php
                        $steps = [
                            ['key' => 'pending', 'icon' => '📝', 'label' => 'Order'],
                            ['key' => 'confirmed', 'icon' => '✅', 'label' => 'Konfirmasi'],
                            ['key' => 'processing', 'icon' => '👨‍🍳', 'label' => 'Proses'],
                            ['key' => 'shipped', 'icon' => '🚚', 'label' => 'Kirim'],
                            ['key' => 'delivered', 'icon' => '🎉', 'label' => 'Selesai'],
                        ];
                        $statusOrder = ['pending' => 0, 'confirmed' => 1, 'processing' => 2, 'ready' => 3, 'shipped' => 3, 'delivered' => 4];
                        $currentStep = $statusOrder[$order->status] ?? 0;
                    @endphp
                    
                    @foreach($steps as $index => $step)
                        <div class="flex flex-col items-center relative z-10 group">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg transition-all duration-500
                                {{ $index <= $currentStep ? 'bg-gradient-to-br from-orange-400 to-red-500 text-white shadow-lg shadow-orange-200 scale-110' : 'bg-gray-200 text-gray-400' }}
                                group-hover:scale-125">
                                {{ $step['icon'] }}
                            </div>
                            <span class="text-xs mt-2 font-semibold transition-colors {{ $index <= $currentStep ? 'text-orange-600' : 'text-gray-400' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Order Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Order Items Card --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden opacity-0 animate-fadeInUp delay-200 card-hover">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-orange-50/30">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 flex items-center gap-3">
                                <span class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-200">🛒</span>
                                Item Pesanan
                            </h3>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm font-medium text-gray-600">{{ $order->orderItems->count() }} item</span>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($order->orderItems as $index => $item)
                            <div class="p-5 item-hover" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                                <div class="flex items-center gap-4">
                                    @if($item->menu->image)
                                        <img src="{{ Storage::url($item->menu->image) }}" 
                                             alt="{{ $item->menu->name }}" 
                                             class="w-20 h-20 rounded-2xl object-cover shadow-md flex-shrink-0 hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                                            <span class="text-3xl">🍽️</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 truncate text-lg">{{ $item->menu->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        @if($item->notes)
                                            <p class="text-xs text-gray-400 mt-2 bg-amber-50 px-3 py-1.5 rounded-lg inline-block border border-amber-100">📝 {{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Info Card --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden opacity-0 animate-fadeInUp delay-300 card-hover">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-blue-50/30">
                        <h3 class="font-bold text-gray-900 flex items-center gap-3">
                            <span class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">📍</span>
                            Informasi Pengiriman
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Penerima</p>
                                    <p class="font-bold text-gray-900 text-lg mt-1">{{ $order->recipient_name }}</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Telepon</p>
                                    <p class="font-semibold text-gray-700 mt-1">{{ $order->phone }}</p>
                                </div>
                                @if($order->courier)
                                <div class="p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Kurir</p>
                                    <p class="font-semibold text-gray-700 mt-1">{{ strtoupper($order->courier) }} {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                                <p class="text-xs text-blue-600 uppercase tracking-wider font-semibold">Alamat Lengkap</p>
                                <p class="text-gray-800 mt-2 leading-relaxed">{{ $order->delivery_address }}</p>
                                @if($order->destination_city || $order->destination_province)
                                    <p class="text-sm text-blue-600 mt-2 font-medium">
                                        📍 {{ $order->destination_district ? $order->destination_district . ', ' : '' }}{{ $order->destination_city }}{{ $order->destination_province ? ', ' . $order->destination_province : '' }}
                                        {{ $order->destination_postal_code ? ' (' . $order->destination_postal_code . ')' : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        
                        {{-- Tracking Section - Shows for all orders except cancelled --}}
                        @if($order->status !== 'cancelled')
                            @php
                                $hasTrackingInfo = $order->tracking_number || $order->biteship_waybill_id;
                                $isInstantCourier = in_array(strtolower($order->courier ?? ''), ['gosend', 'grab', 'gojek']);
                                $hasOrderId = !empty($order->biteship_order_id);
                                // Show tracking button for:
                                // 1. Regular couriers with waybill
                                // 2. Instant couriers with order ID
                                // 3. All instant couriers (to show status even without order ID)
                                $showTrackingButton = $hasTrackingInfo || $isInstantCourier || $hasOrderId;
                            @endphp
                            <div class="mt-6 p-5 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl border border-emerald-200 hover:shadow-lg transition-shadow"
                                 x-data="trackingComponent()">
                                
                                @if($showTrackingButton)
                                    {{-- Ada Resi atau GoSend Order ID --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            @if($isInstantCourier)
                                                <p class="text-xs text-emerald-600 uppercase tracking-wider font-bold flex items-center gap-2">
                                                    <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                                                    Kurir Instan ({{ strtoupper($order->courier) }})
                                                </p>
                                                @if($order->biteship_waybill_id || $order->tracking_number)
                                                    <p class="font-mono font-bold text-emerald-700 text-xl mt-1">{{ $order->biteship_waybill_id ?? $order->tracking_number }}</p>
                                                @else
                                                    <p class="font-mono font-bold text-orange-600 text-lg mt-1">Order: {{ Str::limit($order->biteship_order_id, 15) }}</p>
                                                @endif
                                            @else
                                                <p class="text-xs text-emerald-600 uppercase tracking-wider font-bold flex items-center gap-2">
                                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                                    Nomor Resi
                                                </p>
                                                <p class="font-mono font-bold text-emerald-700 text-xl mt-1">{{ $order->biteship_waybill_id ?? $order->tracking_number }}</p>
                                            @endif
                                        </div>
                                        @if($order->biteship_waybill_id || $order->tracking_number)
                                            <button onclick="copyTrackingNumber('{{ $order->biteship_waybill_id ?? $order->tracking_number }}')"
                                                    class="p-3 bg-white hover:bg-emerald-100 rounded-xl transition-all hover:scale-110 shadow-md hover:shadow-lg border border-emerald-200">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Tombol Lacak Pengiriman -->
                                    <button x-on:click="loadTracking()" 
                                            x-bind:disabled="loading"
                                            class="w-full mt-2 px-4 py-3 text-white font-bold rounded-xl hover:shadow-lg transition-all flex items-center justify-center gap-2 disabled:opacity-50"
                                            style="background: linear-gradient(to right, {{ $isInstantCourier ? '#f97316, #ea580c' : '#10b981, #059669' }});">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <span>{{ $isInstantCourier ? '📍 Lacak Kurir Instan' : '🚚 Lacak Pengiriman' }}</span>
                                    </button>
                                @else
                                    {{-- Belum Ada Resi --}}
                                    <div class="text-center py-2">
                                        <div class="flex items-center justify-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                            </svg>
                                            <p class="text-sm font-semibold text-emerald-700">Lacak Pengiriman</p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            @if($order->status === 'pending')
                                                Menunggu pembayaran. Nomor resi akan tersedia setelah pembayaran dikonfirmasi dan pesanan dikirim.
                                            @elseif(in_array($order->status, ['confirmed', 'processing', 'preparing']))
                                                Pesanan sedang diproses. Nomor resi akan tersedia setelah pesanan dikirim.
                                            @else
                                                Nomor resi belum tersedia. Hubungi customer service jika perlu bantuan.
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Tracking Modal -->
                                <template x-teleport="body">
                                    <div x-show="showModal" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                                         style="background: rgba(0,0,0,0.5);"
                                         @click.self="showModal = false"
                                         @keydown.escape.window="showModal = false">
                                        <div x-show="showModal"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 scale-90"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-90"
                                             class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col">
                                            
                                            <!-- Modal Header -->
                                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-green-50 flex items-center justify-between">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                                        🚚 Lacak Pengiriman
                                                    </h3>
                                                    <p class="text-sm text-gray-600" x-text="'Resi: ' + waybillId"></p>
                                                </div>
                                                <button @click="showModal = false" class="p-2 hover:bg-white rounded-lg transition-colors">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Modal Content -->
                                            <div class="p-6 overflow-y-auto flex-1">
                                                <!-- Error State -->
                                                <template x-if="error">
                                                    <div class="text-center py-8">
                                                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <p class="text-gray-600" x-text="error"></p>
                                                    </div>
                                                </template>
                                                
                                                <!-- Tracking Timeline -->
                                                <template x-if="trackingData && !error">
                                                    <div>
                                                        <!-- Current Status -->
                                                        <div class="mb-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                                                            <p class="text-xs text-emerald-600 uppercase font-bold mb-1">Status Terkini</p>
                                                            <p class="text-lg font-bold text-emerald-700" x-text="formatStatus(trackingData.status)"></p>
                                                        </div>
                                                        
                                                        <!-- Timeline -->
                                                        <div class="space-y-4">
                                                            <template x-for="(item, index) in trackingData.history" :key="index">
                                                                <div class="flex gap-4">
                                                                    <div class="flex flex-col items-center">
                                                                        <div :class="index === 0 ? 'bg-emerald-500' : 'bg-gray-300'" 
                                                                             class="w-3 h-3 rounded-full"></div>
                                                                        <div x-show="index < trackingData.history.length - 1" 
                                                                             class="w-0.5 h-full bg-gray-200 flex-1"></div>
                                                                    </div>
                                                                    <div class="flex-1 pb-4">
                                                                        <p class="font-semibold text-gray-900 text-sm" x-text="item.note"></p>
                                                                        <p class="text-xs text-gray-500 mt-1" x-text="formatDate(item.updated_at)"></p>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        
                                                        <!-- No history yet -->
                                                        <template x-if="!trackingData.history || trackingData.history.length === 0">
                                                            <div class="text-center py-8 text-gray-500">
                                                                <p>Belum ada riwayat tracking.</p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                            
                                            <!-- Modal Footer -->
                                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                                <button @click="showModal = false" 
                                                        class="w-full px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endif
                        
                        @if($order->notes)
                            <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl border border-amber-200">
                                <p class="text-xs text-amber-600 uppercase tracking-wider font-bold mb-2">📝 Catatan Pesanan</p>
                                <p class="text-amber-800">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Payment Summary --}}
            <div class="space-y-6">
                
                {{-- Payment Summary Card --}}
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-purple-50/30">
                        <h3 class="font-bold text-gray-900 flex items-center gap-3">
                            <span class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-200">💳</span>
                            Ringkasan Pembayaran
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center text-sm py-3 px-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Subtotal ({{ $order->orderItems->count() }} item)</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($subtotalMenu, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm py-3 px-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center py-4 px-5 bg-orange-50 rounded-2xl border border-orange-200">
                                <span class="font-bold text-gray-900 text-lg">Total</span>
                                <span class="text-2xl font-black text-orange-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        @if($order->payment)
                            <div class="pt-4 border-t border-gray-200 space-y-4">
                                <div class="flex items-center justify-between text-sm py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500">Metode Pembayaran</span>
                                    <span class="font-semibold text-gray-900 text-right">
                                        @if($order->payment->payment_type)
                                            {{ ucwords(str_replace('_', ' ', $order->payment->payment_type)) }}
                                        @else
                                            {{ $order->payment->paymentMethod->name ?? 'Transfer Bank' }}
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm p-3 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500">Status Pembayaran</span>
                                    @if($isExpired)
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            ⏰ Expired
                                        </span>
                                    @else
                                        @php
                                            $paymentStatus = $order->payment->status ?? 'pending';
                                            $isPaid = in_array($paymentStatus, ['paid', 'confirmed', 'settlement', 'capture']);
                                            $isPending = in_array($paymentStatus, ['pending', 'waiting']);
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold
                                            {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                            @if($isPaid) ✓ Lunas @elseif($isPending) ⏳ Belum Bayar @else ✗ {{ ucfirst($paymentStatus) }} @endif
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between text-sm p-3 rounded-xl {{ $status['light'] }}">
                                    <span class="text-gray-500">Status Pesanan</span>
                                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $status['text'] }} {{ $status['light'] }} border {{ $status['border'] }}">
                                        {{ $status['icon'] }} {{ $status['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($order->status === 'pending' && !$isExpired && $order->payment && $order->payment->paymentMethod)
                        <div class="p-6 pt-0">
                            <a href="{{ route('customer.order.midtrans', $order) }}" 
                               class="block w-full text-center px-6 py-4 text-white font-bold rounded-2xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 btn-shine"
                               style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                💳 Bayar Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Need Help Card --}}
                <div class="bg-gradient-to-br from-purple-100 via-indigo-50 to-pink-100 rounded-3xl p-6 border border-purple-200 card-hover">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto shadow-lg mb-4">
                            <span class="text-3xl">💬</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-lg">Butuh Bantuan?</h4>
                        <p class="text-sm text-gray-600 mt-2 mb-5">Tim kami siap membantu 24/7</p>
                        <a href="{{ route('customer.message.index', ['order_id' => $order->id]) }}" 
                           class="inline-flex items-center justify-center w-full px-5 py-3 bg-white border-2 border-purple-300 text-purple-700 font-bold rounded-xl hover:bg-purple-50 hover:border-purple-400 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyTrackingNumber(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-fadeInUp';
        toast.innerHTML = '✓ Nomor resi berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }).catch(err => {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Nomor resi berhasil disalin: ' + text);
    });
}

// Alpine.js component untuk tracking
function trackingComponent() {
    return {
        loading: false,
        showModal: false,
        error: null,
        trackingData: null,
        waybillId: '{{ $order->biteship_waybill_id ?? $order->tracking_number ?? "" }}',
        
        async loadTracking() {
            this.loading = true;
            this.error = null;
            this.trackingData = null;
            
            try {
                const response = await fetch('{{ route("customer.order.tracking", $order) }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.trackingData = data.data;
                    this.showModal = true;
                } else {
                    this.error = data.message || 'Terjadi kesalahan saat memuat tracking.';
                    this.showModal = true;
                }
            } catch (err) {
                console.error('Tracking error:', err);
                this.error = 'Gagal mengambil data tracking. Silakan coba lagi.';
                this.showModal = true;
            } finally {
                this.loading = false;
            }
        },
        
        formatStatus(status) {
            const statusMap = {
                'confirmed': 'Pesanan Dikonfirmasi',
                'allocated': 'Kurir Dialokasikan',
                'pickingUp': 'Kurir Menjemput',
                'picked': 'Sudah Dijemput',
                'droppingOff': 'Dalam Pengiriman',
                'delivered': 'Terkirim',
                'onHold': 'Ditunda',
                'rejected': 'Ditolak',
                'cancelled': 'Dibatalkan',
                'returned': 'Dikembalikan',
                'courierNotFound': 'Kurir Tidak Ditemukan'
            };
            return statusMap[status] || status;
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('id-ID', options) + ' WIB';
        }
    };
}
</script>
@endpush
@endsection