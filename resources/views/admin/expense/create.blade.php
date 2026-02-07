@extends('layouts.report')

@section('report_content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Biaya Baru</h1>
        <a href="{{ route('admin.expense.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Oops! Ada kesalahan:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <form action="{{ route('admin.expense.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Biaya</label>
                        <input type="date" name="date" id="date" 
                               value="{{ old('date', date('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                        <input type="number" name="amount" id="amount" 
                               value="{{ old('amount') }}"
                               placeholder="50000" step="any"
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input type="text" name="description" id="description" 
                           value="{{ old('description') }}"
                           placeholder="Beli gas elpiji 3kg"
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                </div>

                <div class="mt-6">
                    <label for="chart_of_account_id" class="block text-sm font-medium text-gray-700">Kategori Akun Biaya</label>
                    <select name="chart_of_account_id" id="chart_of_account_id"
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="">-- Pilih Akun Biaya --</option>
                        @foreach ($expenseAccounts as $account)
                        <option value="{{ $account->id }}" {{ old('chart_of_account_id') == $account->id ? 'selected' : '' }}>
                            [{{ $account->code }}] {{ $account->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 text-right">
                <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 custom-gradient text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                        style="background: linear-gradient(to right, #f97316, #ea580c);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
                    Simpan Biaya
                </button>
            </div>
        </form>
    </div>
</div>
@endsection