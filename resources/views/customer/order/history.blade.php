@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="rounded-2xl shadow-lg p-6 mb-8 mt-6" style="background: linear-gradient(to right, #ea580c, #ef4444);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Riwayat Pesanan</h1>
                <p class="mt-1 text-sm text-white/90">Lihat status dan detail pesanan Anda</p>
            </div>
            <div>
                <a href="{{ route('customer.order.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-orange-600 hover:bg-orange-50 font-bold rounded-xl transition-all duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Pesan Lagi
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <form action="{{ route('customer.orders') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Search Box -->
                <div class="md:col-span-5">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari No. Pesanan..."
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-gray-700 placeholder-gray-400 font-medium bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-3">
                    <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-gray-700 font-medium bg-gray-50 focus:bg-white cursor-pointer appearance-none">
                        <option value="all">📝 Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Dikonfirmasi</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>👨‍🍳 Disiapkan</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>🚚 Dalam Perjalanan</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>🎉 Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                </div>

                <!-- Period Filter -->
                <div class="md:col-span-2">
                    <select name="period" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-gray-700 font-medium bg-gray-50 focus:bg-white cursor-pointer appearance-none">
                        <option value="">📅 Semua Waktu</option>
                        <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="md:col-span-2 flex space-x-2">
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'status', 'period']))
                        <a href="{{ route('customer.orders') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-medium transition-all flex items-center justify-center">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->orderItems->count() }} item{{ $order->orderItems->count() > 1 ? 's' : '' }}</p>
                                </div>
                                
                                <!-- Order Status Badge -->
                                <div class="flex flex-col items-end space-y-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                                        @elseif($order->status == 'ready') bg-green-100 text-green-800
                                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($order->status == 'pending') ⏳ Menunggu
                                        @elseif($order->status == 'confirmed') ✅ Terkonfirmasi
                                        @elseif($order->status == 'preparing') 👨‍🍳 Disiapkan
                                        @elseif($order->status == 'ready') 📦 Siap
                                        @elseif($order->status == 'delivered') 🚚 Selesai
                                        @else ❌ Dibatalkan
                                        @endif
                                    </span>
                                    
                                    <!-- Payment Status (jika ada) -->
                                    @if($order->payment)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($order->payment->status === 'confirmed') bg-green-100 text-green-700
                                            @elseif($order->payment->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($order->payment->status === 'failed') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            @if($order->payment->status === 'confirmed') 💳 Dibayar
                                            @elseif($order->payment->status === 'pending') ⏳ Belum Bayar
                                            @elseif($order->payment->status === 'failed') ❌ Gagal Bayar
                                            @else 💳 {{ ucfirst($order->payment->status) }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">{{ $order->formatted_total }}</p>
                                <a href="{{ route('customer.order.show', $order) }}" 
                                   class="text-orange-600 hover:text-orange-500 text-sm font-medium inline-flex items-center">
                                    Lihat Detail 
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-4">
                                        @if($item->menu->image)
                                            <img src="{{ Storage::url($item->menu->image) }}" alt="{{ $item->menu->name }}" 
                                                 class="h-14 w-14 object-cover rounded-xl border border-gray-200">
                                        @else
                                            <div class="h-14 w-14 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center border border-gray-200">
                                                <svg class="h-7 w-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $item->menu->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item->quantity }}x @ {{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</p>
                                            @if($item->notes)
                                                <p class="text-xs text-gray-500 bg-gray-50 rounded px-2 py-1 mt-1 inline-block">
                                                    Catatan: {{ $item->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">{{ $item->formatted_subtotal }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($order->notes)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-sm text-blue-800">
                                        <span class="font-semibold">Catatan Pesanan:</span> {{ $order->notes }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Alamat Pengiriman:</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $order->delivery_address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <svg class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Nomor Telepon:</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $order->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Kurir dan Tracking Number --}}
                            @if($order->courier || $order->tracking_number)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    @if($order->courier)
                                        <div class="flex items-start space-x-3">
                                            <svg class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Kurir:</p>
                                                <p class="text-sm text-gray-600 mt-1">{{ strtoupper($order->courier) }} {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($order->tracking_number)
                                        <div class="flex items-start space-x-3">
                                            <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Nomor Resi:</p>
                                                <p class="text-sm font-mono bg-green-50 text-green-700 px-2 py-1 rounded mt-1 inline-block">{{ $order->tracking_number }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($order->destination_district || $order->destination_city)
                                        <div class="flex items-start space-x-3">
                                            <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Lokasi Tujuan:</p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    @if($order->destination_district)
                                                        {{ $order->destination_district }}{{ $order->destination_city ? ', ' : '' }}
                                                    @endif
                                                    {{ $order->destination_city }}{{ $order->destination_province ? ', ' . $order->destination_province : '' }}
                                                    @if($order->destination_postal_code)
                                                        <span class="text-gray-500">({{ $order->destination_postal_code }})</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex flex-wrap gap-2">
                                @if($order->status == 'pending' && $order->payment && $order->payment->status == 'pending')
                                    {{-- ✅ FIX: Add null check for paymentMethod --}}
                                    @if($order->payment->paymentMethod && $order->payment->paymentMethod->type == 'midtrans')
                                        <a href="{{ route('customer.order.midtrans', $order) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            Bayar Sekarang
                                        </a>
                                    @elseif($order->payment && !$order->payment->paymentMethod)
                                        {{-- ✅ FIX: Handle case when paymentMethod is null --}}
                                        <span class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Metode Pembayaran Tidak Valid
                                        </span>
                                    @endif
                                @endif
                                
                                @if($order->status == 'delivered')
                                    <span class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai
                                    </span>
                                @endif
                                
                                @if($order->status == 'confirmed' || $order->status == 'preparing')
                                    <span class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($order->status == 'confirmed') Sedang Diproses
                                        @else Sedang Disiapkan
                                        @endif
                                    </span>
                                @endif
                                
                                @if($order->status == 'ready')
                                    <span class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-800 text-sm font-medium rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Dalam Perjalanan
                                    </span>
                                    
                                    {{-- Tombol Konfirmasi Pesanan Sampai --}}
                                    <form action="{{ route('customer.order.confirm-delivery', $order) }}" method="POST" class="inline" onsubmit="return confirm('Apakah pesanan sudah sampai dan Anda sudah menerimanya?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Pesanan Telah Sampai
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Reorder Button - Available for all completed orders -->
                                @if(in_array($order->status, ['delivered', 'confirmed', 'preparing', 'ready']))
                                    <form action="{{ route('customer.order.reorder', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-800 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Pesan Lagi
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Check Payment Status Button (for pending payments) -->
                                @if($order->payment && $order->payment->status == 'pending' && $order->payment->midtrans_order_id)
                                    <a href="{{ route('customer.orders', $order) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Cek Status Pembayaran
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                <p class="mt-2 text-sm text-gray-500">Mulai pesan pempek favorit Anda sekarang!</p>
                <div class="mt-6">
                    <a href="{{ route('customer.order.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Mulai Pesan
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection