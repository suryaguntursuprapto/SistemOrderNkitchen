@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-500 via-teal-500 to-blue-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2 animate-slide-in-left">Kelola Menu</h1>
                    <p class="text-green-100 text-lg">Atur dan kelola menu makanan N-Kitchen</p>
                </div>
                <a href="{{ route('admin.menu.create') }}" 
                   class="bg-white text-green-600 px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2 group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Menu</span>
                </a>
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-gray-600 flex items-center">
                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Filter:
                </span>
                <a href="{{ route('admin.menu.index') }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ !request('category') ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-600' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('admin.menu.index', ['category' => $category->id]) }}" 
                       class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ request('category') == $category->id ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-600' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($menus as $menu)
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                <!-- Image -->
                <div class="relative h-48 overflow-hidden">
                    @if($menu->image)
                        <img src="{{ Storage::url($menu->image) }}" 
                             alt="{{ $menu->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-orange-200 to-red-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 left-3">
                        @if($menu->is_available)
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium flex items-center space-x-1">
                                <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                                <span>Tersedia</span>
                            </span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium flex items-center space-x-1">
                                <div class="w-2 h-2 bg-red-300 rounded-full"></div>
                                <span>Tidak Tersedia</span>
                            </span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="bg-blue-500 bg-opacity-90 text-white px-3 py-1 rounded-full text-xs font-medium">
                            {{ $menu->category }}
                        </span>
                    </div>

                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.menu.show', $menu) }}" 
                               class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transform hover:scale-110 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.menu.edit', $menu) }}" 
                               class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 transform hover:scale-110 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"
                                        class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transform hover:scale-110 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-200">
                            {{ $menu->name }}
                        </h3>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-orange-600">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ $menu->description }}
                    </p>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.menu.edit', $menu) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center space-x-1 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada menu</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan menu pertama Anda</p>
                    <a href="{{ route('admin.menu.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 transform hover:scale-105 transition-all duration-200 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah Menu</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menus->hasPages())
        <div class="flex justify-center">
            <nav class="bg-white rounded-2xl shadow-lg border border-gray-100 px-6 py-4">
                {{ $menus->links() }}
            </nav>
        </div>
    @endif
</div>

<style>
@keyframes slide-in-left {
    from { opacity: 0; transform: translateX(-50px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-slide-in-left {
    animation: slide-in-left 0.8s ease-out;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection