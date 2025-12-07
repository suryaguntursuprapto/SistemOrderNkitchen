@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10 text-center">
                <h1 class="text-5xl font-bold mb-4 animate-fade-in-up">Menu N-Kitchen</h1>
                <p class="text-xl text-amber-100 max-w-2xl mx-auto leading-relaxed">
                    Nikmati kelezatan menu Nkitchen yang authentic dengan cita rasa yang tak terlupakan
                </p>
                <div class="mt-6 flex items-center justify-center space-x-4">
                    <div class="flex items-center text-amber-100">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span>Kualitas Premium</span>
                    </div>
                    <div class="flex items-center text-amber-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Selalu Fresh</span>
                    </div>
                    <div class="flex items-center text-amber-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>Rasa Otentik</span>
                    </div>
                </div>
            </div>
            <!-- Floating Elements -->
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-white opacity-5 rounded-full animate-bounce"></div>
            <div class="absolute top-1/2 right-8 w-24 h-24 bg-white opacity-10 rounded-full animate-ping"></div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kategori Menu
            </h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('customer.menu.index') }}" 
                   class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ !request('category') ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-700' }}">
                    Semua Menu
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('customer.menu.index', ['category' => $category->id]) }}" 
                       class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ request('category') == $category->id ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    @if($menus->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
            @foreach($menus as $menu)
                <div class="menu-card group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 overflow-hidden border border-gray-100" data-category="{{ strtolower($menu->category) }}">
                    <!-- Image Container -->
                    <div class="relative h-64 overflow-hidden">
                        @if($menu->image)
                            <img src="{{ Storage::url($menu->image) }}" 
                                 alt="{{ $menu->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-200 via-amber-200 to-red-200 flex items-center justify-center">
                                <svg class="w-20 h-20 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-500"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            @if($menu->is_available)
                                <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-bold bg-green-500 text-white shadow-lg">
                                    <div class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></div>
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-lg">
                                    <div class="w-2 h-2 bg-red-300 rounded-full mr-2"></div>
                                    Habis
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-full text-sm font-bold shadow-lg backdrop-blur-sm">
                                {{ ucfirst($menu->category) }}
                            </span>
                        </div>

                        <!-- Hover Content -->
                        <div class="absolute bottom-4 left-4 right-4 transform translate-y-8 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                            <div class="text-white">
                                <p class="text-sm font-medium mb-2">{{ Str::limit($menu->description, 80) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('customer.menu.show', $menu) }}" 
                                       class="bg-white text-orange-600 px-4 py-2 rounded-full font-semibold hover:bg-orange-50 transition-colors duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Detail</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-300 line-clamp-2">
                                {{ $menu->name }}
                            </h3>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                            {{ $menu->description }}
                        </p>

                        <!-- Price and Action -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-3xl font-bold text-orange-600">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">per porsi</p>
                            </div>
                            
                            <a href="{{ route('customer.menu.show', $menu) }}" 
                               class="bg-gradient-to-r from-orange-500 to-red-500 text-black px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Lihat</span>
                            </a>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-ping"></div>
                    <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-red-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700 animate-pulse"></div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($menus->hasPages())
            <div class="flex justify-center mb-8">
                <nav class="bg-white rounded-2xl shadow-xl border border-gray-100 px-8 py-6">
                    {{ $menus->links() }}
                </nav>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="relative">
                <div class="mx-auto w-32 h-32 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center mb-8 relative overflow-hidden">
                    <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-200 to-red-200 opacity-20 animate-pulse"></div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Menu Tersedia</h3>
                <p class="text-gray-600 text-lg max-w-md mx-auto leading-relaxed">
                    Maaf, saat ini belum ada menu yang tersedia. Silakan kembali lagi nanti untuk melihat menu terbaru kami.
                </p>
                <div class="mt-8">
                    <a href="{{ route('customer.dashboard') }}" 
                       style="background: linear-gradient(to right, #f97316, #ef4444);"
                       class="inline-flex items-center px-8 py-4 text-white font-bold rounded-2xl transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Features Section -->
    <div class="mt-16 bg-gradient-to-r from-gray-50 to-gray-100 rounded-3xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa Memilih N-Kitchen?</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Kami berkomitmen memberikan pengalaman kuliner terbaik dengan kualitas dan pelayanan premium
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kualitas Terjamin</h3>
                <p class="text-gray-600">Menggunakan bahan-bahan segar dan berkualitas tinggi untuk setiap hidangan</p>
            </div>
            
            <div class="text-center group">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Selalu Fresh</h3>
                <p class="text-gray-600">Semua menu dibuat fresh setiap hari dengan resep tradisional yang autentik</p>
            </div>
            
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Rasa Otentik</h3>
                <p class="text-gray-600">Cita rasa yang authentic yang telah diwariskan turun temurun</p>
            </div>
        </div>
    </div>
</div>

<script>
// Category Filter
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuCards = document.querySelectorAll('.menu-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-orange-500', 'text-white', 'shadow-lg');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('active', 'bg-orange-500', 'text-white', 'shadow-lg');
            
            // Filter menu cards
            menuCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                
                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.6s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

// Scroll animations
window.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.menu-card');
    
    cards.forEach(card => {
        const cardTop = card.getBoundingClientRect().top;
        const cardVisible = 150;
        
        if (cardTop < window.innerHeight - cardVisible) {
            card.classList.add('animate-fade-in-up');
        }
    });
});
</script>

<style>
@keyframes fade-in-up {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom hover effects */
.menu-card:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.filter-btn.active {
    transform: scale(1.05);
}

/* Smooth transitions */
* {
    transition: all 0.3s ease;
}
</style>
@endsection