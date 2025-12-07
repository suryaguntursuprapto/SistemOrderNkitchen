@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl p-8 shadow-2xl" style="background: linear-gradient(135deg, #ea580c 0%, #dc2626 50%, #9333ea 100%);">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full transform translate-x-20 -translate-y-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full transform -translate-x-16 translate-y-16"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-14 h-14 rounded-xl shadow-lg mr-4">
                        <div>
                            <h1 class="text-3xl font-bold text-white">Dashboard Admin</h1>
                            <p class="text-white/80">Selamat datang di panel administrasi N-Kitchen</p>
                        </div>
                    </div>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-white/70 text-sm">{{ now()->format('l, d F Y') }}</p>
                    <p class="text-white text-xl font-bold">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        
        <!-- Profit/Loss (Laba-Rugi) - Special Card -->
        <div class="md:col-span-2 lg:col-span-1 relative overflow-hidden rounded-2xl p-6 shadow-xl" style="background: linear-gradient(135deg, {{ $stats['monthly_profit'] >= 0 ? '#059669, #10b981' : '#dc2626, #ef4444' }});">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    @if($stats['monthly_profit'] >= 0)
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    @endif
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">{{ $stats['monthly_profit'] >= 0 ? 'Laba' : 'Rugi' }} - {{ $stats['current_month'] }}</p>
                <p class="text-2xl font-bold text-white">Rp {{ number_format(abs($stats['monthly_profit']), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
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
                    <p class="text-gray-500 text-sm font-medium mb-1">Pesanan Pending</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                    @if($stats['pending_orders'] > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 mt-2">
                        Perlu ditangani
                    </span>
                    @endif
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #10b981, #0d9488);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pesan Belum Dibaca</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['unread_messages'] }}</p>
                    @if($stats['unread_messages'] > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 mt-2">
                        {{ $stats['unread_messages'] }} baru
                    </span>
                    @endif
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.order.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Pesanan</p>
                    <p class="text-xs text-gray-500">Kelola pesanan</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.menu.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Menu</p>
                    <p class="text-xs text-gray-500">Kelola menu</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.report.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-cyan-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #0891b2, #06b6d4);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-cyan-600 transition-colors">Laporan</p>
                    <p class="text-xs text-gray-500">Lihat laporan</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.message.index') }}" class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-purple-200 transition-all duration-300 group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Chat</p>
                    <p class="text-xs text-gray-500">Lihat pesan</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Data Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Pesanan Terbaru
                    </h3>
                    <a href="{{ route('admin.order.index') }}" class="text-white/80 hover:text-white text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                @forelse($recent_orders as $order)
                    <div class="flex items-center justify-between p-4 hover:bg-blue-50/50 rounded-xl transition-colors duration-200 border-l-4 border-blue-500 mb-3 last:mb-0 bg-gray-50/50">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                                #{{ $order->id }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $order->recipient_name ?? $order->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'delivered') bg-green-100 text-green-700
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada pesanan</p>
                        <p class="text-gray-400 text-sm">Pesanan baru akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Pesan Belum Dibaca
                    </h3>
                    <a href="{{ route('admin.message.index') }}" class="text-white/80 hover:text-white text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                @forelse($recent_messages as $message)
                    <div class="flex items-start space-x-4 p-4 hover:bg-purple-50/50 rounded-xl transition-colors duration-200 border-l-4 border-purple-500 mb-3 last:mb-0 bg-gray-50/50">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-900 truncate">{{ $message->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm font-medium text-purple-700 mb-1">{{ $message->subject }}</p>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($message->message, 80) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Tidak ada pesan baru</p>
                        <p class="text-gray-400 text-sm">Pesan pelanggan akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection