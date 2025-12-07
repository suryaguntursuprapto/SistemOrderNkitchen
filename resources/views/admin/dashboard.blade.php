@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2 animate-fade-in">Dashboard Admin</h1>
                <p class="text-orange-100 text-lg">Selamat datang di panel administrasi N-Kitchen</p>
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
    </div>


        <!-- Total Revenue -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-orange-500 via-green-500 to-pink-500 p-8 text-white shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <br>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
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
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pelanggan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pesan Belum Dibaca</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['unread_messages'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Pesanan Terbaru
                </h3>
            </div>
            <div class="p-6">
                @forelse($recent_orders as $order)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border-l-4 border-blue-400 mb-3 last:mb-0">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">#{{ $order->id }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Pesan Belum Dibaca
                </h3>
            </div>
            <div class="p-6">
                @forelse($recent_messages as $message)
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border-l-4 border-purple-400 mb-3 last:mb-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 truncate">{{ $message->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-700 mb-1">{{ $message->subject }}</p>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($message->message, 80) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500">Tidak ada pesan baru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection