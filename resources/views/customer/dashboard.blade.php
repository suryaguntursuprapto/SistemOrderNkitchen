@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl p-8 shadow-2xl" style="background: linear-gradient(135deg, #ea580c 0%, #dc2626 50%, #9333ea 100%);">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full transform translate-x-20 -translate-y-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full transform -translate-x-16 translate-y-16"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between flex-wrap gap-6">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-16 h-16 rounded-xl shadow-lg mr-4">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white mb-1">Selamat Datang {{ auth()->user()->name }}!</h1>
                        </div>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-white/70 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                        <p class="text-white text-xl font-bold">{{ now()->format('H:i') }} WIB</p>
                    </div>
                </div>
                <p class="text-white/80 text-lg mt-4 mb-6">Nikmati kelezatan makanan N-Kitchen dengan layanan terbaik</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.order.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-orange-600 rounded-xl font-semibold shadow-lg hover:bg-orange-50 transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('customer.menu.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/20 text-white rounded-xl font-semibold backdrop-blur-sm hover:bg-white/30 transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Lihat Menu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <!-- Total Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                    <p class="text-sm text-blue-600 mt-1">Sepanjang waktu</p>
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pesanan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                    <p class="text-sm text-orange-600 mt-1">Sedang diproses</p>
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pesanan Selesai</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['delivered_orders'] }}</p>
                    <p class="text-sm text-green-600 mt-1">Berhasil diantar</p>
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('customer.order.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #ea580c, #dc2626);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Pesan</p>
                    <p class="text-xs text-gray-500">Buat pesanan</p>
                </div>
            </div>
        </a>
        <a href="{{ route('customer.menu.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Menu</p>
                    <p class="text-xs text-gray-500">Lihat menu</p>
                </div>
            </div>
        </a>
        <a href="{{ route('customer.orders') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-purple-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Riwayat</p>
                    <p class="text-xs text-gray-500">Pesanan saya</p>
                </div>
            </div>
        </a>
        <a href="{{ route('customer.message.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-green-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Pesan</p>
                    <p class="text-xs text-gray-500">Hubungi kami</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100" style="background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Pesanan Terbaru
                </h3>
                <a href="{{ route('customer.orders') }}" class="text-white/80 hover:text-white text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($recent_orders as $order)
                <div class="p-5 hover:bg-orange-50/50 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background: linear-gradient(135deg, #ea580c, #dc2626);">
                                #{{ $order->id }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Order #{{ $order->order_number ?? $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-700
                                @elseif($order->status == 'processing') bg-orange-100 text-orange-700
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-700
                                @elseif($order->status == 'delivered') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                @if($order->status == 'pending') 🕐 Pending
                                @elseif($order->status == 'confirmed') ✅ Dikonfirmasi
                                @elseif($order->status == 'processing') 👨‍🍳 Diproses
                                @elseif($order->status == 'shipped') 🚚 Dikirim
                                @elseif($order->status == 'delivered') 🎉 Selesai
                                @else ❌ Dibatalkan @endif
                            </span>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->orderItems->take(3) as $item)
                                <div class="flex items-center bg-white rounded-lg px-3 py-2 shadow-sm">
                                    @if($item->menu && $item->menu->image)
                                        <img src="{{ Storage::url($item->menu->image) }}" 
                                             alt="{{ $item->menu->name ?? 'Menu' }}" 
                                             class="w-8 h-8 rounded-lg object-cover mr-2">
                                    @else
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item->menu->name ?? 'Menu' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }}x</p>
                                    </div>
                                </div>
                            @endforeach
                            @if($order->orderItems->count() > 3)
                                <div class="flex items-center bg-orange-100 rounded-lg px-3 py-2 text-orange-700 text-sm font-medium">
                                    +{{ $order->orderItems->count() - 3 }} lainnya
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Yuk, mulai pesan makanan lezat dari N-Kitchen!</p>
                    <a href="{{ route('customer.order.create') }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300" style="background: linear-gradient(135deg, #ea580c, #dc2626);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection