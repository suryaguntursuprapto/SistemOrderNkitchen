@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2 animate-fade-in">Tambah Menu Baru</h1>
                    <p class="text-purple-100 text-lg">Buat menu makanan baru untuk N-Kitchen Pempek</p>
                </div>
                <a href="{{ route('admin.menu.index') }}" 
                   class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-xl font-semibold backdrop-blur-sm hover:bg-opacity-30 transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                Informasi Menu
            </h2>
        </div>

        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Nama Menu
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 group-hover:border-blue-300 @error('name') border-red-500 @enderror"
                               placeholder="Contoh: Pempek Kapal Selam"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Kategori
                        </label>
                        <select name="category_id" 
                                id="category_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 group-hover:border-green-300 @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('admin.category.create') }}" class="text-blue-600 hover:underline">+ Tambah kategori baru</a>
                        </p>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="weight" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Berat per Item (Gram)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                name="weight" 
                                id="weight" 
                                class="w-full pl-6 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 group-hover:border-indigo-300 @error('weight') border-red-500 @enderror" 
                                value="{{ old('weight', $menu->weight ?? 200) }}" 
                                placeholder="200"
                                required min="1">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">gram</span>
                        </div>
                        @error('weight')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="group">
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Harga (Rp)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="price"
                                   value="{{ old('price') }}"
                                   min="0"
                                   step="500"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 group-hover:border-yellow-300 @error('price') border-red-500 @enderror"
                                   placeholder="15000"
                                   required>
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Ketersediaan
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" 
                                       name="is_available" 
                                       value="1" 
                                       {{ old('is_available', '1') == '1' ? 'checked' : '' }}
                                       class="sr-only">
                                <div class="w-5 h-5 border-2 border-green-300 rounded-full flex items-center justify-center group-hover:border-green-400 transition-colors duration-200">
                                    <div class="w-3 h-3 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200" 
                                         style="opacity: {{ old('is_available', '1') == '1' ? '1' : '0' }}"></div>
                                </div>
                                <span class="ml-3 text-green-700 font-medium">Tersedia</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" 
                                       name="is_available" 
                                       value="0" 
                                       {{ old('is_available') == '0' ? 'checked' : '' }}
                                       class="sr-only">
                                <div class="w-5 h-5 border-2 border-red-300 rounded-full flex items-center justify-center group-hover:border-red-400 transition-colors duration-200">
                                    <div class="w-3 h-3 bg-red-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200" 
                                         style="opacity: {{ old('is_available') == '0' ? '1' : '0' }}"></div>
                                </div>
                                <span class="ml-3 text-red-700 font-medium">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="group">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Deskripsi
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 group-hover:border-indigo-300 resize-none @error('description') border-red-500 @enderror"
                                  placeholder="Deskripsikan menu ini dengan detail..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Gambar Menu (Opsional)
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   name="image" 
                                   id="image"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <label for="image" 
                                   class="w-full h-48 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-pink-400 hover:bg-pink-50 transition-all duration-200 group-hover:border-pink-300">
                                <div id="image-preview" class="hidden w-full h-full rounded-xl overflow-hidden">
                                    <img id="preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                                </div>
                                <div id="upload-placeholder" class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium">Klik untuk upload gambar</p>
                                    <p class="text-gray-400 text-sm mt-1">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </label>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                <a href="{{ route('admin.menu.index') }}" 
                   class="px-6 py-3 text-gray-600 font-semibold rounded-xl border border-gray-300 hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Batal</span>
                </a>
                
                <button type="submit" 
                        class="px-8 py-3 bg-indigo-500 text-white font-semibold rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Menu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const placeholder = document.getElementById('upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Radio button styling
document.querySelectorAll('input[name="is_available"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="is_available"]').forEach(r => {
            const indicator = r.parentElement.querySelector('div > div');
            indicator.style.opacity = r.checked ? '1' : '0';
        });
    });
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}
</style>
@endsection