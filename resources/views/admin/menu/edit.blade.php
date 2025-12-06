@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <a href="{{ route('admin.menu.index') }}" 
                   class="group flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md border border-gray-200 hover:shadow-lg hover:bg-orange-50 transition-all duration-300">
                    <svg class="h-5 w-5 text-gray-600 group-hover:text-orange-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                        Edit Menu
                    </h1>
                    <p class="mt-2 text-lg text-gray-600">{{ $menu->name }}</p>
                    <p class="text-sm text-gray-500">Perbarui informasi menu pempek Anda</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-black">Form Edit Menu</h2>
                </div>
            </div>

            <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700">
                                Nama Menu
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative transform transition-transform duration-300">
                                <input type="text" name="name" id="name" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('name', $menu->name) }}" 
                                       placeholder="Contoh: Pempek Lenjer">
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                            </div>
                            @error('name')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="category_id" class="block text-sm font-semibold text-gray-700">
                                Kategori
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative transform transition-transform duration-300">
                                <select name="category_id" id="category_id" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('category_id') border-red-500 ring-2 ring-red-200 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->icon }} {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                            </div>
                            <p class="text-xs text-gray-500">
                                <a href="{{ route('admin.category.create') }}" class="text-blue-600 hover:underline">+ Tambah kategori baru</a>
                            </p>
                            @error('category_id')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="weight" class="block text-sm font-semibold text-gray-700">
                                Berat per Item (Gram)
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative transform transition-transform duration-300">
                                <input type="number" name="weight" id="weight" required min="1" step="1"
                                       class="block w-full pl-6 pr-14 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('weight') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('weight', $menu->weight) }}" 
                                       placeholder="200">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">gram</span>
                                </div>
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                            </div>
                            @error('weight')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="price" class="block text-sm font-semibold text-gray-700">
                                Harga
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative transform transition-transform duration-300">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium">Rp</span>
                                </div>
                                <input type="number" name="price" id="price" required min="0" step="1000"
                                       class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('price') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('price', $menu->price) }}" 
                                       placeholder="15000">
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                            </div>
                            @error('price')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            Deskripsi
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative transform transition-transform duration-300">
                            <textarea name="description" id="description" rows="4" required
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 resize-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Deskripsikan menu ini dengan detail yang menarik...">{{ old('description', $menu->description) }}</textarea>
                            <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                        </div>
                        @error('description')
                            <div class="flex items-center space-x-2 mt-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Status & Gambar</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="is_available" class="block text-sm font-semibold text-gray-700">Status Ketersediaan</label>
                            <div class="relative transform transition-transform duration-300">
                                <select name="is_available" id="is_available"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300">
                                    <option value="1" {{ old('is_available', $menu->is_available) == '1' ? 'selected' : '' }}>
                                        ✅ Tersedia
                                    </option>
                                    <option value="0" {{ old('is_available', $menu->is_available) == '0' ? 'selected' : '' }}>
                                        ❌ Tidak Tersedia
                                    </option>
                                </select>
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-300 pointer-events-none"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="image" class="block text-sm font-semibold text-gray-700">
                            {{ $menu->image ? 'Ganti Gambar Menu (Opsional)' : 'Upload Gambar Menu' }}
                        </label>
                        
                        <div class="bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-300 hover:border-orange-400 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <label for="image" class="cursor-pointer inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500 transition-all duration-300">
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Pilih Gambar
                                    <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                                </label>
                                
                                <div id="file-info" class="hidden">
                                    <div class="flex items-center space-x-3 bg-white rounded-lg px-4 py-2 border border-green-200 shadow-sm">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p id="file-name" class="text-sm font-medium text-gray-900 truncate"></p>
                                            <p id="file-details" class="text-xs text-gray-500"></p>
                                        </div>
                                        <button type="button" id="remove-file" class="flex-shrink-0 text-xs text-red-600 hover:text-red-800 font-medium underline">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="default-info" class="text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Format: PNG, JPG, GIF • Maksimal 2MB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @error('image')
                            <div class="flex items-center space-x-2 mt-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    @if($menu->image)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Gambar Saat Ini</label>
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($menu->image) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="h-32 w-32 object-cover rounded-xl shadow-md border-2 border-white">
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-yellow-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-orange-700">
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileDetails = document.getElementById('file-details');
    const defaultInfo = document.getElementById('default-info');
    const removeFileBtn = document.getElementById('remove-file');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            console.log('File selected:', file.name, file.type, file.size);
            
            if (validateFile(file)) {
                showFileInfo(file);
            } else {
                resetFileInput();
            }
        } else {
            resetFileInput();
        }
    });

    // Handle remove file
    removeFileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        resetFileInput();
    });

    function validateFile(file) {
        // Check file type
        if (!file.type.startsWith('image/')) {
            showNotification('Silakan pilih file gambar (PNG, JPG, GIF, WEBP)', 'error');
            return false;
        }
        
        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            showNotification('Ukuran file maksimal 2MB', 'error');
            return false;
        }
        
        return true;
    }

    function showFileInfo(file) {
        // Update file info
        fileName.textContent = file.name;
        
        // Format file size and type
        const fileSize = formatFileSize(file.size);
        const fileType = file.type.split('/')[1].toUpperCase();
        fileDetails.textContent = `${fileType} • ${fileSize}`;
        
        // Show file info, hide default info
        fileInfo.classList.remove('hidden');
        defaultInfo.classList.add('hidden');
        
        // Add animation
        fileInfo.classList.add('animate-fadeIn');
        
        console.log('File info displayed:', file.name);
    }

    function resetFileInput() {
        // Clear file input
        fileInput.value = '';
        
        // Hide file info, show default info
        fileInfo.classList.add('hidden');
        defaultInfo.classList.remove('hidden');
        
        console.log('File input reset');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg border ${
            type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
            'bg-blue-50 border-blue-200 text-blue-800'
        } transform translate-x-full transition-transform duration-300`;
        
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'error' ? 
                        '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>' :
                        '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                    }
                </svg>
                <p class="text-sm font-medium">${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Add smooth focus transitions
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.relative').classList.add('ring-2', 'ring-offset-2', 'ring-yellow-300');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.relative').classList.remove('ring-2', 'ring-offset-2', 'ring-yellow-300');
        });
    });

    // Debug: Log form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Add loading state
        submitBtn.innerHTML = `
            <svg class="animate-spin mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengupdate...
        `;
        submitBtn.disabled = true;
        
        console.log('Form submitted.');
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endsection