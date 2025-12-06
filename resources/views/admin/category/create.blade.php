@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="rounded-2xl shadow-lg p-6 mb-8 mt-6" style="background: linear-gradient(to right, #8b5cf6, #6366f1);">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('admin.category.index') }}" class="inline-flex items-center text-sm font-medium text-white/80 hover:text-white transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </nav>
                <h1 class="text-3xl font-bold text-white">➕ Tambah Kategori</h1>
                <p class="mt-1 text-sm text-white/90">Buat kategori baru untuk menu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.category.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                       placeholder="Contoh: Pempek, Minuman, Snack">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Icon (Emoji)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                       placeholder="Contoh: 🍤 🥤 🍩">
                <p class="mt-1 text-xs text-gray-500">Gunakan emoji untuk icon kategori. Tekan Windows+. atau Cmd+Ctrl+Space untuk emoji picker.</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                          placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampil</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                <p class="mt-1 text-xs text-gray-500">Kategori dengan urutan lebih kecil akan tampil lebih dulu.</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                       class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">Kategori Aktif</label>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.category.index') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-colors shadow-md">
                    💾 Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
