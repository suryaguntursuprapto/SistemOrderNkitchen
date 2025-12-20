@extends('layouts.app')

@section('content')

@php
    // Variabel ini sudah dihitung di Controller Anda (orderCreate)
    // Kita hanya perlu mereferensikan variabel yang tersedia di view.
    $subtotalMenu = $totalAmount;
    // TotalWeight dan ItemValue sudah tersedia dari compact() di Controller.
    
    // Kosongkan field agar pelanggan mengisi data baru setiap checkout
    $defaultRecipientName = old('recipient_name', '');
    $defaultAddress = old('delivery_address', '');
    $defaultPhone = old('phone', '');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-gray-900" x-data="checkoutForm()">
    <div class="rounded-2xl shadow-lg p-6 mb-8 mt-6" style="background: linear-gradient(to right, #ea580c, #ef4444);">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('customer.order.index') }}" class="inline-flex items-center text-sm font-medium text-white/80 hover:text-white transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Menu
                            </a>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-white">Checkout Pesanan</h1>
                <p class="mt-1 text-sm text-white/90">Lengkapi data pengiriman dan pilih metode pembayaran</p>
            </div>
            
            <div class="hidden md:block">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 21"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    <form action="{{ route('customer.order.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf
        
        <!-- Kolom Kiri: Form Detail Pengiriman -->
        <!-- Di mobile: order-1 = muncul pertama -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden order-1 lg:order-1">
            <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: linear-gradient(to right, #3b82f6, #2563eb);">
                <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Detail Pengiriman & Alamat
                </h3>
            </div>

            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                
                <!-- Nama Penerima -->
                <div>
                    <label for="recipient_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="recipient_name" required
                           minlength="3" maxlength="100"
                           pattern="[a-zA-Z\s\.]+"
                           oninput="this.value = this.value.replace(/[^a-zA-Z\s\.]/g, '')"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('recipient_name') border-red-500 bg-red-50 @enderror"
                           value="{{ old('recipient_name', $defaultRecipientName) }}" 
                           placeholder="Contoh: Budi Santoso">
                    <p class="text-xs text-gray-500 mt-1">Minimal 3 karakter, hanya huruf dan spasi</p>
                    @error('recipient_name')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nomor Telepon Penerima <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <input type="tel" name="phone" id="phone" required
                               inputmode="tel"
                               pattern="(\+62|62|0)[0-9]{9,13}"
                               oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                               class="w-full pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                               value="{{ old('phone', $defaultPhone) }}" 
                               placeholder="08123456789">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: 08xx / 628xx / +628xx (10-15 digit)</p>
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Divider Pengiriman -->
                <div class="flex items-center gap-3 pt-3 pb-1">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Pengiriman</h4>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <!-- Destination Select -->
                <div>
                    <label for="destination_id_select2" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kota / Kecamatan Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select name="destination_id" id="destination_id_select2" x-model="selectedDestinationId"
                            class="w-full text-base @error('destination_id') border-red-500 @enderror" required>
                        @if(old('destination_id'))
                             <option value="{{ old('destination_id') }}" selected>Memuat lokasi...</option>
                        @else
                             <option value="">Ketik nama kota/kecamatan (min. 3 huruf)</option>
                        @endif
                    </select>
                    <p id="destination-error-feedback" class="text-red-500 text-sm mt-1 hidden"></p>
                    @error('destination_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kurir Selection - Mobile Grid -->
                <div> 
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Pilih Kurir <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                        
                        <!-- GoSend - Hanya muncul untuk wilayah Karawang -->
                        <label for="gosend" 
                            x-show="isLocalKarawang"
                            x-transition
                            :class="{'border-green-500 bg-green-50 shadow-md ring-2 ring-green-200': selectedCourier === 'gosend'}"
                            class="flex items-center justify-center px-3 py-3 border-2 border-green-300 rounded-xl cursor-pointer hover:bg-green-50 active:bg-green-100 transition-all duration-200 text-sm bg-green-50">
                            <input type="radio" name="courier" id="gosend" value="gosend" 
                                x-model="selectedCourier" 
                                @change="checkShipping()" 
                                class="sr-only">
                            <span class="font-medium text-center text-green-700">⚡ GoSend</span>
                        </label>
                        
                        <!-- JNE -->
                        <label for="jne" 
                            :class="{'border-orange-500 bg-orange-50 shadow-md ring-2 ring-orange-200': selectedCourier === 'jne'}"
                            class="flex items-center justify-center px-3 py-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 active:bg-gray-100 transition-all duration-200 text-sm">
                            <input type="radio" name="courier" id="jne" value="jne" 
                                x-model="selectedCourier" 
                                @change="checkShipping()" 
                                class="sr-only" required>
                            <span class="font-medium text-center">JNE</span>
                        </label>
                        
                        <!-- SiCepat -->
                        <label for="sicepat" 
                            :class="{'border-orange-500 bg-orange-50 shadow-md ring-2 ring-orange-200': selectedCourier === 'sicepat'}"
                            class="flex items-center justify-center px-3 py-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 active:bg-gray-100 transition-all duration-200 text-sm">
                            <input type="radio" name="courier" id="sicepat" value="sicepat" 
                                x-model="selectedCourier" 
                                @change="checkShipping()" 
                                class="sr-only">
                            <span class="font-medium text-center">SiCepat</span>
                        </label>
                        
                        <!-- J&T -->
                        <label for="jnt" 
                            :class="{'border-orange-500 bg-orange-50 shadow-md ring-2 ring-orange-200': selectedCourier === 'jnt'}"
                            class="flex items-center justify-center px-3 py-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 active:bg-gray-100 transition-all duration-200 text-sm">
                            <input type="radio" name="courier" id="jnt" value="jnt" 
                                x-model="selectedCourier" 
                                @change="checkShipping()" 
                                class="sr-only">
                            <span class="font-medium text-center">J&T</span>
                        </label>
                        
                    </div>
                    
                    <!-- Info GoSend - Hanya muncul untuk Karawang -->
                    <p x-show="isLocalKarawang" x-transition class="text-xs text-green-600 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        ⚡ GoSend tersedia untuk pengiriman instan (1-2 jam) ke Karawang
                    </p>
                </div>
                
                <!-- GoSend Map Picker - Tampil saat GoSend dipilih -->
                <div x-show="selectedCourier === 'gosend' && selectedDestinationId" x-transition class="mb-4">
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-green-800 flex items-center gap-2">
                                📍 Pilih Lokasi Pengiriman GoSend
                            </h4>
                            <button type="button" @click="getMyLocation()" class="text-xs bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                                📍 Lokasi Saya
                            </button>
                        </div>
                        <p class="text-xs text-green-700 mb-3">Pindahkan pin merah ke lokasi pengiriman yang tepat, atau klik di peta.</p>
                        
                        <!-- Map Container -->
                        <div id="gosend-inline-map" class="w-full h-64 rounded-xl border-2 border-green-300 mb-3"></div>
                        
                        <!-- Koordinat terpilih -->
                        <div class="flex items-center justify-between bg-white rounded-lg p-2 text-sm">
                            <span class="text-gray-600">
                                Koordinat: <span class="font-mono font-medium text-green-700" x-text="selectedDestinationLatitude && selectedDestinationLongitude ? parseFloat(selectedDestinationLatitude).toFixed(6) + ', ' + parseFloat(selectedDestinationLongitude).toFixed(6) : 'Belum dipilih'"></span>
                            </span>
                            <button type="button" @click="confirmGoSendLocation()" 
                                :disabled="!selectedDestinationLatitude || !selectedDestinationLongitude"
                                :class="selectedDestinationLatitude && selectedDestinationLongitude ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'"
                                class="px-4 py-2 text-white text-sm font-medium rounded-lg transition">
                                ✓ Konfirmasi Lokasi
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Layanan Pengiriman -->
                <div x-show="selectedCourier && !loading" x-transition>
                    <label for="shipping_service" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Layanan Pengiriman <span class="text-red-500">*</span>
                    </label>
                    <select name="shipping_service" id="shipping_service" x-model="selectedService" @change="updateTotal()"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('shipping_service') border-red-500 @enderror" required>
                        <option value="">-- Pilih Layanan --</option>
                    </select>
                    <div x-show="error" class="text-red-500 text-sm mt-2 p-2 bg-red-50 rounded-lg" x-text="error"></div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex items-center justify-center gap-2 p-4 bg-orange-50 rounded-xl">
                    <div class="animate-spin h-5 w-5 border-2 border-orange-500 rounded-full border-t-transparent"></div>
                    <span class="text-sm text-orange-700 font-medium">Mencari ongkos kirim...</span>
                </div>
                
                <!-- Alamat Detail -->
                <div>
                    <label for="delivery_address" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="delivery_address" id="delivery_address" required rows="3"
                              minlength="15" maxlength="500"
                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none transition-all @error('delivery_address') border-red-500 bg-red-50 @enderror"
                              placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Dekat Masjid Al-Ikhlas">{{ old('delivery_address', $defaultAddress) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimal 15 karakter. Sertakan nama jalan, nomor, RT/RW, dan patokan</p>
                    @error('delivery_address')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Catatan -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Catatan Pesanan <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                              maxlength="500"
                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none transition-all @error('notes') border-red-500 @enderror"
                              placeholder="Catatan khusus, misal: jangan pakai sambal, packing extra, dll">{{ old('notes', session('reorder_data.notes')) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <input type="hidden" name="total_weight" value="{{ $totalWeight }}"> 
                <input type="hidden" name="shipping_cost" x-model="shippingCost">
                <input type="hidden" name="destination_name" x-model="selectedDestinationText">
                <input type="hidden" name="destination_province" x-model="selectedDestinationProvince">
                <input type="hidden" name="destination_postal_code" x-model="selectedDestinationPostalCode">
                <input type="hidden" name="destination_latitude" x-model="selectedDestinationLatitude">
                <input type="hidden" name="destination_longitude" x-model="selectedDestinationLongitude">
                <input type="hidden" name="item_value" value="{{ $itemValue }}"> </div>
        </div>
        
        <!-- Kolom Kanan: Ringkasan, Pembayaran, Tombol Bayar -->
        <!-- Di mobile: order-2 = muncul di bawah form -->
        <!-- Di desktop: order-2 = tetap di kanan -->
        <div class="order-2 lg:order-2 lg:sticky lg:top-4">
            
            <!-- Ringkasan Pesanan -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-4 sm:mb-6">
                <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: linear-gradient(to right, #f97316, #ea580c);">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Ringkasan Pesanan
                        </h3>
                        <span class="bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ count($validCartItems) }} Item
                        </span>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6 text-gray-900 bg-white">
                    <!-- Daftar Item -->
                    <div class="space-y-3 mb-4 max-h-48 sm:max-h-60 overflow-y-auto pr-1">
                        @foreach($validCartItems as $item)
                            <div class="flex items-start gap-3 p-2.5 sm:p-3 rounded-xl border border-gray-100" style="background: linear-gradient(to right, #f9fafb, #f3f4f6);">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-cover shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0" style="background: linear-gradient(to bottom right, #fb923c, #ef4444);">
                                        {{ substr($item['name'], 0, 1) }}
                                    </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $item['name'] }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-gray-500">{{ $item['quantity'] }}x</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">{{ number_format($item['total_weight'] / 1000, 1, ',', '.') }} kg</span>
                                    </div>
                                    <p class="font-bold text-orange-600 text-sm mt-1">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Summary -->
                    <div class="p-4 rounded-xl border border-orange-100" style="background: linear-gradient(to bottom right, #fff7ed, #fefce8);">
                        <div class="flex justify-between items-center mb-2 text-sm">
                            <span class="text-gray-600">Subtotal Menu</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($subtotalMenu, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-sm">
                            <span class="text-gray-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Ongkos Kirim
                            </span>
                            <span class="font-medium text-gray-800" x-text="formatRupiah(shippingCost)">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-sm">
                            <span class="text-gray-600">Total Berat</span>
                            <span class="font-medium text-gray-800">{{ number_format($totalWeight / 1000, 2, ',', '.') }} kg</span>
                        </div>
                        <div class="border-t border-orange-200 my-3"></div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900 text-base sm:text-lg">Total Bayar</span>
                            <span class="font-bold text-orange-600 text-lg sm:text-xl" x-text="formatRupiah(grandTotal)">Rp {{ number_format($subtotalMenu, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Pembayaran -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-4 sm:mb-6">
                <div class="bg-indigo-500 px-4 sm:px-6 py-3 sm:py-4">
                    <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Metode Pembayaran
                    </h3>
                </div>
                <div class="p-4 sm:p-6 text-gray-900 bg-white">
                    <div class="space-y-2 sm:space-y-3">
                        @foreach($paymentMethods as $method)
                            <label class="flex items-center p-3 sm:p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 @error('payment_method_id') border-red-500 @enderror"
                                   :class="selectedPaymentMethod == {{ $method->id }} ? 'border-indigo-500 bg-indigo-50 shadow-md ring-2 ring-indigo-200' : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50'">
                                <input type="radio" name="payment_method_id" value="{{ $method->id }}" 
                                       x-model="selectedPaymentMethod"
                                       class="sr-only"
                                       required>
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-xl mr-3 sm:mr-4 flex-shrink-0"
                                     :class="selectedPaymentMethod == {{ $method->id }} ? 'bg-indigo-500' : 'bg-gray-100'">
                                    @if($method->type == 'midtrans')
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" :class="selectedPaymentMethod == {{ $method->id }} ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" :class="selectedPaymentMethod == {{ $method->id }} ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">{{ $method->name }}</h4>
                                    @if($method->account_number)
                                        <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $method->account_number }} - {{ $method->account_name }}</p>
                                    @endif
                                    @if($method->type == 'midtrans')
                                        <p class="text-xs text-indigo-600 font-medium">Kartu, Virtual Account, e-Wallet</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 ml-2"
                                     :class="selectedPaymentMethod == {{ $method->id }} ? 'text-indigo-500' : 'text-gray-300'">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method_id')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <!-- Tombol Bayar -->
            <div class="sticky bottom-0 bg-white/80 backdrop-blur-lg p-4 -mx-4 sm:mx-0 sm:p-0 sm:bg-transparent sm:backdrop-blur-none border-t sm:border-0 border-gray-200">
                <button type="submit" :disabled="!selectedService || !selectedPaymentMethod"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3.5 sm:py-4 rounded-xl text-base sm:text-lg font-bold transition-all duration-300 shadow-lg hover:shadow-xl disabled:bg-gray-300 disabled:shadow-none disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="`Bayar ${formatRupiah(grandTotal)}`">Bayar Sekarang</span>
                </button>
                <p class="text-center text-xs text-gray-500 mt-2 hidden sm:block">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Transaksi aman dan terenkripsi
                </p>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Map Modal Template - will be cloned and shown by JS -->
<template id="gosend-map-modal-template">
    <div id="gosend-map-modal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeGoSendMapModal()"></div>
            
            <div class="relative z-50 w-full max-w-2xl p-6 mx-auto bg-white rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">📍 Pilih Lokasi Pengiriman GoSend</h3>
                    <button onclick="closeGoSendMapModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 mb-3">Pindahkan pin ke lokasi pengiriman yang tepat untuk GoSend.</p>
                
                <!-- Map Container -->
                <div id="gosend-map" class="w-full h-80 rounded-xl border-2 border-gray-200 mb-4"></div>
                
                <!-- Koordinat terpilih -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
                    <p class="text-gray-600">
                        Koordinat: <span id="gosend-coords-display" class="font-mono font-medium text-gray-900">-6.3676, 107.3024</span>
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="closeGoSendMapModal()" 
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button onclick="confirmGoSendLocation()" 
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition">
                        ✓ Gunakan Lokasi Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
@endpush

@push('scripts')
<!-- Leaflet CSS & JS for Map Picker -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Fix untuk Select2 styling -->
<style>
    /* Hide native select completely */
    select#destination_id_select2.select2-hidden-accessible {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
    }
    
    /* Container styling */
    .select2-container {
        width: 100% !important;
    }
    
    /* Main selection box */
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        padding: 0 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 12px !important;
        background-color: #fff !important;
    }
    
    .select2-container--default .select2-selection--single:hover {
        border-color: #f97316 !important;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #f97316 !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2) !important;
    }
    
    /* Selected text */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px !important;
        padding-left: 16px !important;
        padding-right: 40px !important;
        color: #374151 !important;
        font-size: 14px !important;
    }
    
    /* Arrow */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 12px !important;
    }
    
    /* Dropdown */
    .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1) !important;
        margin-top: 4px !important;
        z-index: 9999 !important;
        background-color: #fff !important;
    }
    
    .select2-container--open .select2-dropdown {
        z-index: 10000 !important;
    }
    
    /* Search input */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        font-size: 14px !important;
    }
    
    /* Results */
    .select2-results__option {
        padding: 10px 16px !important;
        font-size: 14px !important;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #fff7ed !important;
        color: #ea580c !important;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f97316 !important;
        color: #fff !important;
    }
</style>

<script>
    const subtotalMenu = {{ $subtotalMenu }};
    const totalWeight = {{ $totalWeight }};
    const itemValue = {{ $itemValue }};
    
    // Inisialisasi jQuery Global untuk CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function checkoutForm() {
        return {
            subtotalMenu: subtotalMenu,
            totalWeight: totalWeight,
            itemValue: itemValue,
            shippingCost: 0,
            grandTotal: subtotalMenu,
            selectedDestinationId: '{{ old('destination_id') }}',
            selectedDestinationAreaId: '', // Biteship area_id
            selectedDestinationText: '', // Nama lokasi tujuan
            selectedDestinationProvince: '', // Provinsi tujuan
            selectedDestinationPostalCode: '', // Kode pos tujuan
            selectedDestinationLatitude: '', // Latitude untuk instant couriers
            selectedDestinationLongitude: '', // Longitude untuk instant couriers
            isLocalKarawang: false, // Apakah lokasi dalam jangkauan lokal
            selectedCourier: '',
            selectedService: '',
            selectedPaymentMethod: null,
            availableServices: [],
            loading: false,
            error: null,
            
            // Kurir tersedia - GoSend menggunakan Biteship API (gojek)
            availableCouriers: [
                { id: 'gosend', name: '⚡ GoSend (Instant)' },
                { id: 'jne', name: 'JNE' },
                { id: 'sicepat', name: 'SiCepat' },
                { id: 'jnt', name: 'J&T Express' },
            ],

            initSelect2() {
                const selectElement = $('#destination_id_select2');
                
                if (selectElement.data('select2')) {
                    selectElement.select2('destroy');
                }

                selectElement.select2({
                    placeholder: "Ketik nama kota/kecamatan (min. 3 huruf)...",
                    minimumInputLength: 3,
                    width: '100%',
                    
                    // Gunakan body sebagai parent jika ada konflik z-index (paling aman)
                    dropdownParent: $('body'), 
                    
                    templateResult: function(data) {
                        return data.text; 
                    },
                    templateSelection: function(data) {
                        return data.text; 
                    },
                    
                    ajax: {
                        url: '{{ route("customer.api.search_destination") }}',
                        dataType: 'json',
                        delay: 350, 
                        data: function (params) {
                            return { keyword: params.term };
                        },
                        processResults: (data) => {
                            hideDestinationError();
                            
                            if (data.error) {
                                console.error("Search API Error (Controller Response):", data.error);
                                showDestinationError(data.error); 
                                return { results: [] };
                            }
                            
                            return { 
                                results: data.results || [] 
                            }; 
                        },
                        error: (jqXHR, status, error) => {
                            console.error("Select2 AJAX Network Error:", status, error);
                            showDestinationError('Gagal koneksi server. Cek log atau Develop Menu.');
                        },
                        cache: true
                    }
                })
                .on('select2:select', (e) => {
                    // 1. Dapatkan elemen select DOM asli
                    const select = document.getElementById('destination_id_select2');
                    
                    // 2. Setel nilai elemen DOM asli
                    select.value = e.params.data.id;
                    
                    // 3. Panggil event 'change' di DOM
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    
                    // 4. Update variabel Alpine secara eksplisit
                    this.selectedDestinationId = e.params.data.id;
                    
                    // 5. Simpan data lokasi tujuan dari Biteship
                    this.selectedDestinationText = e.params.data.text || '';
                    this.selectedDestinationAreaId = e.params.data.id || ''; // Biteship area_id
                    this.isLocalKarawang = this.selectedDestinationText.toUpperCase().includes('KARAWANG');
                    
                    // 5b. Ambil data dari API
                    if (e.params.data.postal_code) {
                        this.selectedDestinationPostalCode = e.params.data.postal_code;
                    } else {
                        this.selectedDestinationPostalCode = '';
                    }
                    
                    // 5c. Simpan koordinat untuk instant couriers (GoSend, Grab)
                    this.selectedDestinationLatitude = e.params.data.latitude || '';
                    this.selectedDestinationLongitude = e.params.data.longitude || '';
                    
                    if (e.params.data.province) {
                        this.selectedDestinationProvince = e.params.data.province;
                    } else {
                        // Fallback: extract dari text jika API tidak return province
                        const parts = this.selectedDestinationText.split(',');
                        if (parts.length >= 3) {
                            this.selectedDestinationProvince = parts[2].trim();
                        } else {
                            this.selectedDestinationProvince = '';
                        }
                    }
                    
                    console.log('Destination data:', {
                        text: this.selectedDestinationText,
                        province: this.selectedDestinationProvince,
                        postalCode: this.selectedDestinationPostalCode,
                        latitude: this.selectedDestinationLatitude,
                        longitude: this.selectedDestinationLongitude
                    });
                    
                    // 6. Update list kurir berdasarkan lokasi
                    this.updateCourierList();

                    if (this.selectedDestinationId) {
                        this.checkShipping(); 
                    }
                    hideDestinationError();
                })
                .on('select2:unselect', (e) => {
                    this.selectedDestinationId = '';
                    this.selectedDestinationText = '';
                    this.selectedDestinationAreaId = '';
                    this.selectedDestinationProvince = '';
                    this.selectedDestinationPostalCode = '';
                    this.isLocalKarawang = false;
                    this.updateCourierList();
                    this.resetShipping();
                });
                
                if ('{{ old('destination_id') }}') {
                    selectElement.val('{{ old('destination_id') }}').trigger('change');
                }
            },

            formatRupiah(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            },

            // Initilize inline GoSend map when visible
            gosendMapInstance: null,
            gosendMarker: null,
            gosendMapInitialized: false,
            
            initGoSendMap() {
                const mapContainer = document.getElementById('gosend-inline-map');
                if (!mapContainer) {
                    console.log('GoSend map container not found');
                    return;
                }
                
                // Jika sudah ada map, destroy dulu
                if (this.gosendMapInstance) {
                    this.gosendMapInstance.remove();
                    this.gosendMapInstance = null;
                    this.gosendMapInitialized = false;
                }
                
                // Default ke Karawang jika belum ada koordinat
                const lat = parseFloat(this.selectedDestinationLatitude) || -6.3676;
                const lng = parseFloat(this.selectedDestinationLongitude) || 107.3024;
                
                console.log('Initializing GoSend map at:', lat, lng);
                
                // Initialize Leaflet map
                this.gosendMapInstance = L.map('gosend-inline-map', {
                    center: [lat, lng],
                    zoom: 15
                });
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                    maxZoom: 19
                }).addTo(this.gosendMapInstance);
                
                // Add draggable marker
                this.gosendMarker = L.marker([lat, lng], { draggable: true }).addTo(this.gosendMapInstance);
                
                // Red icon
                const redIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                this.gosendMarker.setIcon(redIcon);
                this.gosendMarker.bindPopup('📍 Seret ke lokasi pengiriman').openPopup();
                
                // Update coordinates when marker dragged
                this.gosendMarker.on('dragend', (e) => {
                    const latlng = e.target.getLatLng();
                    this.selectedDestinationLatitude = latlng.lat;
                    this.selectedDestinationLongitude = latlng.lng;
                });
                
                // Click on map to move marker
                this.gosendMapInstance.on('click', (e) => {
                    this.selectedDestinationLatitude = e.latlng.lat;
                    this.selectedDestinationLongitude = e.latlng.lng;
                    this.gosendMarker.setLatLng(e.latlng);
                });
                
                // Set initial coordinates
                this.selectedDestinationLatitude = lat;
                this.selectedDestinationLongitude = lng;
                
                // PENTING: invalidateSize setelah container visible agar tiles terrender
                setTimeout(() => {
                    if (this.gosendMapInstance) {
                        this.gosendMapInstance.invalidateSize();
                        console.log('Map invalidateSize called');
                    }
                }, 300);
                
                this.gosendMapInitialized = true;
                console.log('GoSend map initialized successfully');
            },
            
            getMyLocation() {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung GPS');
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.selectedDestinationLatitude = pos.coords.latitude;
                        this.selectedDestinationLongitude = pos.coords.longitude;
                        
                        if (this.gosendMapInstance && this.gosendMarker) {
                            this.gosendMapInstance.setView([pos.coords.latitude, pos.coords.longitude], 16);
                            this.gosendMarker.setLatLng([pos.coords.latitude, pos.coords.longitude]);
                        }
                        console.log('Got my location:', pos.coords);
                    },
                    (err) => {
                        alert('Gagal mendapatkan lokasi: ' + err.message);
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            },
            
            confirmGoSendLocation() {
                if (!this.selectedDestinationLatitude || !this.selectedDestinationLongitude) {
                    alert('Pilih lokasi di peta terlebih dahulu');
                    return;
                }
                console.log('Confirmed GoSend location:', this.selectedDestinationLatitude, this.selectedDestinationLongitude);
                // Trigger shipping check
                this.checkShipping();
            },

            // Fungsi untuk mendapatkan lokasi dari browser (untuk GoSend)
            getBrowserLocation() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Geolocation tidak didukung browser ini'));
                        return;
                    }
                    
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            resolve({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            });
                        },
                        (error) => {
                            let errorMessage = 'Gagal mendapatkan lokasi';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Akses lokasi ditolak. Izinkan akses lokasi untuk menggunakan GoSend.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Lokasi tidak tersedia. Pastikan GPS aktif.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = 'Waktu permintaan lokasi habis. Coba lagi.';
                                    break;
                            }
                            reject(new Error(errorMessage));
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000 // Cache 5 menit
                        }
                    );
                });
            },

            // FUNGSI UNTUK MERENDER LAYANAN KURIR KE DALAM SELECT
            renderShippingServices() {
                const select = document.getElementById('shipping_service');
                // Hapus opsi lama (kecuali opsi default)
                while (select.options.length > 1) {
                    select.remove(1);
                }

                if (this.availableServices.length > 0) {
                    this.availableServices.forEach(service => {
                        const option = document.createElement('option');
                        
                        // KOREKSI: Menggunakan properti cost, service, dan name yang BENAR dari API Komerce
                        option.value = service.service_code || service.service; 
                        option.setAttribute('data-cost', service.cost);
                        
                        // Memastikan format tampilan
                        option.textContent = `${service.name} (${service.service}) - Rp ${this.formatRupiah(service.cost)} (Est: ${service.etd})`;
                        
                        select.appendChild(option);
                    });
                }
            },

            // 1. Hitung Ongkir
            async checkShipping() {
                if (!this.selectedDestinationId || !this.selectedCourier) return;
                
                // Untuk GoSend, init map dan tunggu user konfirmasi lokasi
                if (this.selectedCourier === 'gosend') {
                    // Init map jika belum
                    if (!this.gosendMapInitialized) {
                        this.$nextTick(() => {
                            setTimeout(() => this.initGoSendMap(), 200);
                        });
                    }
                    // Jika koordinat belum ada, tunggu user pilih di map
                    if (!this.selectedDestinationLatitude || !this.selectedDestinationLongitude) {
                        return; // Tunggu user konfirmasi lokasi
                    }
                }
                
                this.loading = true;
                this.error = null;
                this.availableServices = [];
                this.selectedService = '';
                this.updateTotal();

                try {
                    const endpoint = '{{ route("customer.api.shipping") }}';
                    const res = await fetch(endpoint, {
                        method: 'POST',
                        headers: this.getFetchHeaders(),
                        body: JSON.stringify({
                            // Biteship: gunakan postal_code atau area_id
                            destination_postal_code: this.selectedDestinationPostalCode,
                            destination_area_id: this.selectedDestinationAreaId,
                            // Koordinat untuk instant couriers (GoSend, Grab)
                            destination_latitude: this.selectedDestinationLatitude,
                            destination_longitude: this.selectedDestinationLongitude,
                            courier: this.selectedCourier,
                            weight: this.totalWeight,
                            item_value: this.itemValue
                        })
                    });
                    
                    const data = await res.json();
                    
                    console.log('Biteship API Shipping Status:', res.status);
                    console.log('Biteship API Shipping Response:', data); 
                    
                    if (!res.ok) {
                        this.error = data.error || 'Gagal mengambil biaya kirim. Cek Log Server.';
                        this.availableServices = [];
                        this.renderShippingServices();
                        this.loading = false;
                        return;
                    }
                    
                    if (data.error || data.length === 0) {
                        this.error = data.error || 'Tidak ada layanan pengiriman yang ditemukan untuk kurir ini.';
                        this.availableServices = [];
                        this.renderShippingServices();
                        this.loading = false;
                        return;
                    }

                    // Biteship API mengembalikan array langsung dari controller
                    let allServices = Array.isArray(data) ? data : [];
                    
                    // Tampilkan semua layanan, atau filter termurah saja
                    if (allServices.length > 0) {
                        allServices.sort((a, b) => (a.cost || 0) - (b.cost || 0));
                        // Tampilkan semua layanan (atau ambil hanya 1 termurah)
                        this.availableServices = allServices;
                    } else {
                        this.availableServices = [];
                    }
                    
                    // Panggil fungsi render setelah data berhasil di-fetch
                    this.renderShippingServices();

                } catch (e) {
                    console.error("Check Shipping Fetch Exception:", e);
                    this.error = 'Gagal koneksi server ongkos kirim.';
                }
                this.loading = false;
            },

            getFetchHeaders() {
                return {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
            },

            // 2. Update Total
            updateTotal() {
                const selectElement = document.getElementById('shipping_service');
                if (selectElement && selectElement.selectedIndex > 0) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const cost = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
                    this.shippingCost = cost;
                } else {
                    this.shippingCost = 0;
                }
                
                this.grandTotal = this.subtotalMenu + this.shippingCost;
            },

            // 3. Reset
            resetShipping() {
                this.shippingCost = 0;
                this.availableServices = [];
                this.selectedService = '';
                this.updateTotal();
                this.renderShippingServices(); // Panggil render reset
            },
            
            // 4. Update Kurir List berdasarkan lokasi
            updateCourierList() {
                // Kurir standar
                const standardCouriers = [
                    { id: 'jne', name: 'JNE' },
                    { id: 'sicepat', name: 'SiCepat' },
                    { id: 'jnt', name: 'J&T Express' },
                ];
                
                // Jika lokasi di Karawang, tambahkan GoSend
                if (this.isLocalKarawang) {
                    this.availableCouriers = [
                        { id: 'gosend', name: '⚡ GoSend (Lokal)', isLocal: true },
                        ...standardCouriers
                    ];
                } else {
                    this.availableCouriers = standardCouriers;
                }
                
                // Reset pilihan kurir jika tidak tersedia lagi
                const courierIds = this.availableCouriers.map(c => c.id);
                if (!courierIds.includes(this.selectedCourier)) {
                    this.selectedCourier = '';
                    this.resetShipping();
                }
            }
        }
    }
    
    // PERBAIKAN TIMING: Panggil initSelect2 setelah DOM siap
    $(document).ready(function() {
        // Tunggu Alpine selesai init
        setTimeout(() => {
            const alpineEl = document.querySelector('[x-data]');
            if (!alpineEl || !alpineEl._x_dataStack) {
                console.error('Alpine component not found');
                return;
            }
            
            const alpineData = alpineEl._x_dataStack[0];
            
            // Init Select2 dengan context yang benar
            const selectElement = $('#destination_id_select2');
            
            if (selectElement.data('select2')) {
                selectElement.select2('destroy');
            }

            selectElement.select2({
                placeholder: "Ketik nama kota/kecamatan (min. 3 huruf)...",
                minimumInputLength: 3,
                width: '100%',
                dropdownParent: $('body'), 
                
                ajax: {
                    url: '{{ route("customer.api.search_destination") }}',
                    dataType: 'json',
                    delay: 350, 
                    data: function (params) {
                        return { keyword: params.term };
                    },
                    processResults: (data) => {
                        hideDestinationError();
                        
                        if (data.error) {
                            console.error("Search API Error:", data.error);
                            showDestinationError(data.error); 
                            return { results: [] };
                        }
                        
                        return { results: data.results || [] }; 
                    },
                    error: (jqXHR, status, error) => {
                        console.error("Select2 AJAX Error:", status, error);
                        showDestinationError('Gagal koneksi server.');
                    },
                    cache: true
                }
            })
            .on('select2:select', (e) => {
                const select = document.getElementById('destination_id_select2');
                select.value = e.params.data.id;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Update Alpine data langsung
                alpineData.selectedDestinationId = e.params.data.id;
                alpineData.selectedDestinationAreaId = e.params.data.id; // Biteship area_id
                alpineData.selectedDestinationText = e.params.data.text || '';
                alpineData.isLocalKarawang = alpineData.selectedDestinationText.toUpperCase().includes('KARAWANG');
                
                // Update list kurir berdasarkan lokasi
                alpineData.updateCourierList();
                
                // Ambil provinsi dan kode pos dari data Biteship API
                if (e.params.data.postal_code) {
                    alpineData.selectedDestinationPostalCode = e.params.data.postal_code;
                } else {
                    alpineData.selectedDestinationPostalCode = '';
                }
                
                if (e.params.data.province) {
                    alpineData.selectedDestinationProvince = e.params.data.province;
                } else {
                    // Fallback: extract dari text jika API tidak return province
                    const parts = alpineData.selectedDestinationText.split(',');
                    if (parts.length >= 3) {
                        alpineData.selectedDestinationProvince = parts[2].trim();
                    } else {
                        alpineData.selectedDestinationProvince = '';
                    }
                }
                
                console.log('Biteship Destination data:', {
                    areaId: alpineData.selectedDestinationAreaId,
                    text: alpineData.selectedDestinationText,
                    province: alpineData.selectedDestinationProvince,
                    postalCode: alpineData.selectedDestinationPostalCode
                });
                
                if (alpineData.selectedDestinationId && alpineData.selectedCourier) {
                    alpineData.checkShipping();
                }
                hideDestinationError();
            })
            .on('select2:unselect', (e) => {
                alpineData.selectedDestinationId = '';
                alpineData.selectedDestinationAreaId = '';
                alpineData.selectedDestinationText = '';
                alpineData.selectedDestinationProvince = '';
                alpineData.selectedDestinationPostalCode = '';
                alpineData.isLocalKarawang = false;
                alpineData.updateCourierList();
                alpineData.resetShipping();
            });
            
        }, 100);
    });

    // Helper untuk menampilkan error di bawah Select2
    function showDestinationError(message) {
        $('#destination-error-feedback').text(message).removeClass('hidden');
    }

    function hideDestinationError() {
        $('#destination-error-feedback').addClass('hidden');
    }

    // GoSend Map Picker - Plain JS Implementation
    let gosendMap = null;
    let gosendMarker = null;
    let gosendSelectedLat = -6.3676;
    let gosendSelectedLng = 107.3024;
    let gosendOnConfirmCallback = null;

    window.openGoSendMapPicker = function(initialLat, initialLng, onConfirm) {
        gosendSelectedLat = initialLat || -6.3676;
        gosendSelectedLng = initialLng || 107.3024;
        gosendOnConfirmCallback = onConfirm;

        // Clone template and add to body
        const template = document.getElementById('gosend-map-modal-template');
        const modal = template.content.cloneNode(true);
        document.body.appendChild(modal);

        // Initialize map after modal is added to DOM
        setTimeout(() => {
            initGoSendMap();
        }, 100);
    };

    function initGoSendMap() {
        // Remove existing map if any
        if (gosendMap) {
            gosendMap.remove();
            gosendMap = null;
        }

        // Initialize Leaflet map
        gosendMap = L.map('gosend-map').setView([gosendSelectedLat, gosendSelectedLng], 15);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(gosendMap);

        // Add draggable marker
        gosendMarker = L.marker([gosendSelectedLat, gosendSelectedLng], {
            draggable: true
        }).addTo(gosendMap);

        // Custom red marker icon
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        gosendMarker.setIcon(redIcon);
        gosendMarker.bindPopup('📍 Seret pin ke lokasi pengiriman').openPopup();

        // Update coordinates display when marker is dragged
        gosendMarker.on('dragend', (e) => {
            const latlng = e.target.getLatLng();
            gosendSelectedLat = latlng.lat;
            gosendSelectedLng = latlng.lng;
            updateCoordsDisplay();
        });

        // Allow click on map to move marker
        gosendMap.on('click', (e) => {
            gosendSelectedLat = e.latlng.lat;
            gosendSelectedLng = e.latlng.lng;
            gosendMarker.setLatLng(e.latlng);
            updateCoordsDisplay();
        });

        updateCoordsDisplay();
    }

    function updateCoordsDisplay() {
        const display = document.getElementById('gosend-coords-display');
        if (display) {
            display.textContent = gosendSelectedLat.toFixed(6) + ', ' + gosendSelectedLng.toFixed(6);
        }
    }

    window.confirmGoSendLocation = function() {
        if (gosendOnConfirmCallback) {
            gosendOnConfirmCallback({
                latitude: gosendSelectedLat,
                longitude: gosendSelectedLng
            });
        }
        closeGoSendMapModal();
    };

    window.closeGoSendMapModal = function() {
        // Remove map
        if (gosendMap) {
            gosendMap.remove();
            gosendMap = null;
        }
        // Remove modal from DOM
        const modal = document.getElementById('gosend-map-modal');
        if (modal) {
            modal.remove();
        }
    };
</script>
@endpush