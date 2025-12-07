@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Catatan Pembelian</h1>
                        <p class="text-blue-200 text-sm">Purchases Management</p>
                    </div>
                </div>
                <a href="{{ route('admin.purchases.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-xl text-sm font-semibold shadow-lg hover:bg-blue-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Catat Pembelian Baru
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center" role="alert">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl flex items-center" role="alert">
            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pembelian</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $purchases->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Nilai</p>
                    <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($purchases->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Lunas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $purchases->where('status', 'paid')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Hutang</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $purchases->where('status', 'unpaid')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Pemasok / No. Faktur
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                    {{ $purchase->purchase_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <p class="text-gray-900 font-medium">{{ $purchase->supplier_name ?? 'N/A' }}</p>
                                <p class="text-gray-500 text-xs">{{ $purchase->invoice_number ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($purchase->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Hutang
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 font-bold text-right">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.purchases.show', $purchase->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                    <form action="{{ route('admin.purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data pembelian</p>
                                    <p class="text-gray-400 text-sm mt-1">Klik tombol "Catat Pembelian Baru" untuk menambahkan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($purchases->hasPages())
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
                {!! $purchases->links() !!}
            </div>
        </div>
    @endif
</div>
@endsection