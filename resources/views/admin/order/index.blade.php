@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2 animate-fade-in">Kelola Pesanan</h1>
                <p class="text-blue-100 text-lg">Pantau dan kelola semua pesanan pelanggan dengan mudah</p>
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
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
                        <p class="text-sm font-medium text-gray-600 mb-1">Menunggu Konfirmasi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preparing Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Sedang Disiapkan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['preparing'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ready Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Dalam Perjalanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['ready'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v0M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m0 0V6a2 2 0 00-2-2H9.414a1 1 0 00-.707.293L7.293 5.707A1 1 0 006.586 6H5a2 2 0 00-2 2v0"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-600 rounded-xl blur-lg opacity-25 group-hover:opacity-40 transition-all duration-300"></div>
            <div class="relative bg-white rounded-xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['delivered'] }}</p>
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

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('admin.order.index') }}" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Cari: No. Pesanan, Nama, Telepon, No. Resi...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="flex flex-wrap items-center gap-3">
                    <select name="status" class="block pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent rounded-xl" style="color: #374151;">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>📋 Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🕐 Menunggu Konfirmasi</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Dikonfirmasi</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>👨‍🍳 Sedang Disiapkan</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>🚚 Dalam Perjalanan</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>🎉 Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>

                    <!-- Period Filter -->
                    <select name="period" class="block pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent rounded-xl" style="color: #374151;">
                        <option value="" {{ !request('period') ? 'selected' : '' }}>📅 Semua Waktu</option>
                        <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>📆 Hari Ini</option>
                        <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>📆 Minggu Ini</option>
                        <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>📆 Bulan Ini</option>
                        <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>📆 Tahun Ini</option>
                    </select>

                    <!-- Filter Button -->
                    <button type="submit" class="px-6 py-3 text-white font-semibold rounded-xl transition-all duration-200 hover:opacity-90" style="background: linear-gradient(to right, #3b82f6, #6366f1);">
                        🔍 Cari
                    </button>

                    @if(request()->hasAny(['search', 'status', 'period']))
                        <a href="{{ route('admin.order.index') }}" class="px-4 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all duration-200">
                            ✕ Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <!-- Order Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">#{{ substr($order->order_number, -4) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $order->order_number }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>{{ $order->user->name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Total Amount -->
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>      
                                    @if($order->payment)
                                        <div class="flex items-center justify-end space-x-2 mt-1">
                                            @if($order->payment->isMidtransPayment())
                                                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                </div>
                                            @elseif($order->payment->paymentMethod && $order->payment->paymentMethod->type == 'bank_transfer')
                                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <span class="text-xs text-gray-500">
                                                {{ $order->payment->paymentMethod->name ?? 'Unknown Payment Method' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status Update -->
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.order.update', $order) }}" method="POST" class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="updateStatus(this)"
                                                class="text-sm border-0 rounded-full px-4 py-2 font-bold focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200
                                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800 hover:bg-blue-200
                                                @elseif($order->status == 'preparing') bg-orange-100 text-orange-800 hover:bg-orange-200
                                                @elseif($order->status == 'ready') bg-purple-100 text-purple-800 hover:bg-purple-200
                                                @elseif($order->status == 'delivered') bg-green-100 text-green-800 hover:bg-green-200
                                                @else bg-red-100 text-red-800 hover:bg-red-200
                                                @endif">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🕐 Menunggu Konfirmasi</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Dikonfirmasi</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>👨‍🍳 Sedang Disiapkan</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>🚚 Dalam Perjalanan</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🎉 Selesai</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                        </select>
                                    </form>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.order.show', $order) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="deleteOrder({{ $order->id }})" 
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Content -->
                    <div class="p-6">
                        <!-- Order Items -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Item Pesanan
                            </h4>
                            <div class="space-y-3">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                        <div class="flex items-center space-x-4">
                                            @if($item->menu->image)
                                                <img src="{{ Storage::url($item->menu->image) }}" 
                                                     alt="{{ $item->menu->name }}" 
                                                     class="h-12 w-12 object-cover rounded-lg shadow-lg">
                                            @else
                                                <div class="h-12 w-12 bg-gradient-to-br from-orange-200 to-red-200 rounded-lg flex items-center justify-center shadow-lg">
                                                    <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900">{{ $item->menu->name }}</h5>
                                                <p class="text-sm text-gray-600">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                @if($item->notes)
                                                    <p class="text-xs text-gray-500 bg-yellow-50 px-2 py-1 rounded mt-1">
                                                        <span class="font-medium">Catatan:</span> {{ $item->notes }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-lg text-gray-900">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Customer & Delivery Info -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Customer Info -->
                            <div class="bg-blue-50 rounded-xl p-4">
                                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Informasi Pelanggan
                                </h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-700">Nama:</span>
                                        <span class="text-gray-900">{{ $order->user->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-700">Email:</span>
                                        <span class="text-gray-900">{{ $order->user->email }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-700">Telepon:</span>
                                        <span class="text-gray-900">{{ $order->phone }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="bg-green-50 rounded-xl p-4">
                                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Informasi Pengiriman
                                </h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-start space-x-2">
                                        <span class="font-medium text-gray-700">Alamat:</span>
                                        <span class="text-gray-900">{{ $order->delivery_address }}</span>
                                    </div>
                                    @if($order->courier)
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium text-gray-700">Kurir:</span>
                                            <span class="text-gray-900 uppercase">{{ $order->courier }} {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }}</span>
                                        </div>
                                    @endif
                                    @if($order->tracking_number)
                                        <div class="mt-3 p-3 rounded-lg" style="background: linear-gradient(to right, #dcfce7, #bbf7d0);">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                    </svg>
                                                    <span class="font-bold text-green-800">No. Resi:</span>
                                                </div>
                                                <button onclick="copyToClipboard('{{ $order->tracking_number }}')" class="text-green-600 hover:text-green-800 text-xs font-medium">
                                                    📋 Copy
                                                </button>
                                            </div>
                                            <p class="text-lg font-mono font-bold text-green-800 mt-1">{{ $order->tracking_number }}</p>
                                        </div>
                                    @else
                                        @if(in_array($order->status, ['confirmed', 'preparing']))
                                            <div class="mt-2 p-2 bg-yellow-100 rounded-lg">
                                                <p class="text-xs text-yellow-800">⚠️ No. Resi belum diinput. <a href="{{ route('admin.order.show', $order) }}" class="underline font-semibold">Input Resi</a></p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        @if($order->payment)
                            <div class="bg-purple-50 rounded-xl p-4 mb-6">
                                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    Informasi Pembayaran
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Metode:</span>
                                        <p class="text-gray-900">{{ $order->payment->paymentMethod->name ?? 'Metode tidak diketahui' }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Status:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->payment->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->payment->status == 'confirmed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Jumlah:</span>
                                        <p class="text-gray-900 font-semibold">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Order Notes -->
                        @if($order->notes)
                            <div class="bg-yellow-50 rounded-xl p-4 border-l-4 border-yellow-400">
                                <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Catatan Pesanan
                                </h5>
                                <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="bg-white rounded-2xl shadow-xl border border-gray-100 px-6 py-4">
                {{ $orders->links() }}
            </nav>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="mx-auto w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-8 relative overflow-hidden">
                <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <div class="absolute inset-0 bg-gradient-to-r from-blue-200 to-purple-200 opacity-20 animate-pulse"></div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Pesanan</h3>
            <p class="text-gray-600 text-lg max-w-md mx-auto leading-relaxed">
                Pesanan dari pelanggan akan muncul di sini. Tunggu pesanan pertama masuk!
            </p>
        </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.99-.833-2.46 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Hapus Pesanan</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:ml-10 sm:pl-4 sm:flex">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button onclick="closeDeleteModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Update Status
function updateStatus(select) {
    if (confirm('Yakin ingin mengubah status pesanan?')) {
        select.closest('form').submit();
    }
}

// Delete Order
function deleteOrder(orderId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/order/${orderId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Auto refresh every 60 seconds (increased to avoid interruption)
setInterval(function() {
    // Only refresh if no modals are open
    if (document.getElementById('deleteModal').classList.contains('hidden')) {
        // Refresh the page silently
        window.location.reload();
    }
}, 60000);

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-fade-in';
        notification.innerHTML = '✅ No. Resi berhasil disalin!';
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.remove();
        }, 2000);
    }).catch(function(err) {
        alert('Gagal menyalin: ' + err);
    });
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.status-form select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}
</style>
@endsection