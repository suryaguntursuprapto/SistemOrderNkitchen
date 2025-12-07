@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Kelola Biaya</h1>
                        <p class="text-red-200 text-sm">Expenses Management</p>
                    </div>
                </div>
                <a href="{{ route('admin.expense.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-red-600 rounded-xl text-sm font-semibold shadow-lg hover:bg-red-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Biaya Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Biaya</p>
                    <p class="text-2xl font-bold text-red-600">{{ $expenses->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                    <p class="text-xl font-bold text-orange-600">Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Periode</p>
                    <p class="text-lg font-bold text-gray-700">{{ now()->format('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-red-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    {{ $expense->date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $expense->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    {{ $expense->chartOfAccount->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-700 font-bold text-right">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <form action="{{ route('admin.expense.destroy', $expense) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus biaya ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data biaya</p>
                                    <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Biaya Baru" untuk menambahkan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($expenses->hasPages())
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
                {!! $expenses->links() !!}
            </div>
        </div>
    @endif
</div>
@endsection