@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2 animate-slide-in">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-teal-100 text-lg">Nikmati kelezatan makanan N-Kitchen dengan layanan terbaik</p>
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('customer.menu.index') }}" 
                       class="bg-white bg-opacity-20 text-black px-6 py-3 rounded-xl font-semibold backdrop-blur-sm hover:bg-opacity-30 transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>Lihat Menu</span>
                    </a>
                    <a href="{{ route('customer.order.index') }}" 
                       class="bg-white bg-opacity-20 text-black px-6 py-3 rounded-xl font-semibold backdrop-blur-sm hover:bg-opacity-30 transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Pesanan Saya</span>
                    </a>
                </div>
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                        <p class="text-sm text-blue-600 mt-1">Sepanjang waktu</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pesanan Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                        <p class="text-sm text-orange-600 mt-1">Sedang diproses</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pesanan Selesai</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['delivered_orders'] }}</p>
                        <p class="text-sm text-green-600 mt-1">Berhasil diantar</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Pesanan Terbaru
                </h3>
                <a href="{{ route('customer.order.index') }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center space-x-1 hover:underline">
                    <span>Lihat Semua</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($recent_orders as $order)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-600 font-bold">#{{ $order->id }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                                @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($order->status == 'pending') 🕐 Pending
                                @elseif($order->status == 'confirmed') ✅ Dikonfirmasi
                                @elseif($order->status == 'preparing') 👨‍🍳 Sedang Dimasak
                                @elseif($order->status == 'ready') 🍽️ Siap Antar
                                @elseif($order->status == 'delivered') 🎉 Selesai
                                @else ❌ Dibatalkan @endif
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Item Pesanan
                        </h4>
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                    <div class="flex items-center space-x-3">
                                        @if($item->menu->image)
                                            <img src="{{ Storage::url($item->menu->image) }}" 
                                                 alt="{{ $item->menu->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }}x - Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Details -->
                    @if($order->delivery_address)
                        <div class="mt-4 flex items-start space-x-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium">Alamat Pengiriman:</p>
                                <p>{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Yuk, mulai pesan makanan lezat dari N-Kitchen!</p>
                    <a href="{{ route('customer.menu.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-black font-semibold rounded-xl hover:from-teal-600 hover:to-blue-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>Lihat Menu</span>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-slide-in {
    animation: slide-in 1s ease-out;
}
</style>
@endsection