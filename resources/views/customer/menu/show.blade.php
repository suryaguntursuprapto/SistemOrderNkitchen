@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <div class="py-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('customer.menu.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-orange-600 md:ml-2 transition-colors duration-200">Menu</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $menu->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="lg:grid lg:grid-cols-2 lg:gap-0">
            <!-- Image Section -->
            <div class="relative">
                @if($menu->image)
                    <img src="{{ Storage::url($menu->image) }}" 
                         alt="{{ $menu->name }}" 
                         class="w-full h-96 lg:h-full object-cover">
                @else
                    <div class="w-full h-96 lg:h-full bg-gradient-to-br from-orange-300 via-amber-300 to-red-300 flex items-center justify-center relative overflow-hidden">
                        <svg class="w-32 h-32 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <!-- Decorative circles -->
                        <div class="absolute top-8 right-8 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse"></div>
                        <div class="absolute bottom-8 left-8 w-16 h-16 bg-white opacity-10 rounded-full animate-bounce"></div>
                        <div class="absolute top-1/2 left-8 w-12 h-12 bg-white opacity-10 rounded-full animate-ping"></div>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-6 left-6">
                    @if($menu->is_available)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-500 text-white shadow-lg backdrop-blur-sm">
                            <div class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-lg backdrop-blur-sm">
                            <div class="w-2 h-2 bg-red-300 rounded-full mr-2"></div>
                            Tidak Tersedia
                        </span>
                    @endif
                </div>

                <!-- Category Badge -->
                <div class="absolute top-6 right-6">
                    <span class="bg-white bg-opacity-90 text-gray-800 px-4 py-2 rounded-full text-sm font-bold shadow-lg backdrop-blur-sm">
                        {{ ucfirst($menu->category) }}
                    </span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8 lg:p-12 flex flex-col justify-center">
                <div class="max-w-xl">
                    <!-- Menu Name -->
                    <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                        {{ $menu->name }}
                    </h1>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-baseline space-x-2">
                            <span class="text-5xl font-bold text-orange-600">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </span>
                            <span class="text-lg text-gray-500 font-medium">per porsi</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Deskripsi
                        </h3>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            {{ $menu->description }}
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Keunggulan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Bahan segar dan berkualitas tinggi</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Resep tradisional autentik Palembang</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Dibuat fresh setiap hari</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Tanpa pengawet dan MSG</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('customer.menu.index') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-8 py-4 rounded-2xl font-bold transition-all duration-200 flex items-center justify-center space-x-3 group">
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali ke Menu</span>
                        </a>
                        
                        @if($menu->is_available)
                            <a href="{{ route('customer.order.index') }}" 
                               class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-black px-8 py-4 rounded-2xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Lanjutkan Memesan</span>
                            </a>
                        @else
                            <div class="flex-1 bg-gray-300 text-gray-500 px-8 py-4 rounded-2xl font-bold flex items-center justify-center space-x-3 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                <span>Tidak Tersedia</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info Section -->
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ingredients Info -->
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Bahan Utama</h3>
            </div>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-orange-400 rounded-full mr-3"></span>
                    Ikan tenggiri segar
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-orange-400 rounded-full mr-3"></span>
                    Tepung sagu pilihan
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-orange-400 rounded-full mr-3"></span>
                    Bumbu rempah tradisional
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-orange-400 rounded-full mr-3"></span>
                    Kuah cuko asli
                </li>
            </ul>
        </div>

        <!-- Nutrition Info -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Nutrisi</h3>
            </div>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                    Tinggi protein
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                    Rendah lemak
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                    Kaya omega-3
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                    Bebas kolesterol
                </li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Hubungi Kami</h3>
            </div>
            <div class="space-y-3 text-gray-700">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>0812-3456-7890</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Jl. Sudirman No. 123</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>08:00 - 21:00 WIB</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Menu Section -->
    <div class="mt-16 mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Menu Lainnya</h2>
            <p class="text-gray-600 text-lg">Jelajahi menu lezat lainnya dari N-Kitchen</p>
        </div>
        
        <div class="text-center">
            <a href="{{ route('customer.menu.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-red-500 text-green font-bold rounded-2xl hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Lihat Semua Menu</span>
            </a>
        </div>
    </div>
</div>

<style>
/* Smooth animations */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(135deg, #f97316, #dc2626);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #f97316;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #ea580c;
}
</style>
@endsection