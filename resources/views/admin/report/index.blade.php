@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Ringkasan Keuangan</h1>
                        <p class="text-cyan-200 text-sm">Financial Summary</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm text-cyan-200">Periode:</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
            <!-- Quick Period Buttons -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Cepat</label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.report.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-cyan-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-cyan-50">
                        Bulan Lalu
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->startOfQuarter()->format('Y-m-d'), 'end_date' => now()->endOfQuarter()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-cyan-50">
                        Kuartal Ini
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-cyan-50">
                        Tahun Ini
                    </a>
                </div>
            </div>
            
            <!-- Custom Date Filter -->
            <form action="{{ route('admin.report.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Excel
                    </a>
                    <button type="button" onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                <div class="w-full h-full rounded-full bg-green-100 opacity-50"></div>
            </div>
            <div class="relative">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <p class="ml-4 text-sm font-medium text-gray-500">Total Penjualan</p>
                </div>
                <p class="text-3xl font-bold text-green-600">
                    Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                <div class="w-full h-full rounded-full bg-red-100 opacity-50"></div>
            </div>
            <div class="relative">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <p class="ml-4 text-sm font-medium text-gray-500">Total Biaya & Pembelian</p>
                </div>
                <p class="text-3xl font-bold text-red-600">
                    Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}
                </p>
            </div>
        </div>
        
        <div class="rounded-xl shadow-lg p-6 relative overflow-hidden" style="background: linear-gradient(135deg, {{ $summary['profit'] >= 0 ? '#10b981, #059669' : '#ef4444, #dc2626' }});">
            <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                <div class="w-full h-full rounded-full bg-white opacity-20"></div>
            </div>
            <div class="relative">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        @if($summary['profit'] >= 0)
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        @else
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        @endif
                    </div>
                    <p class="ml-4 text-sm font-medium text-white/80">
                        {{ $summary['profit'] >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                    </p>
                </div>
                <p class="text-3xl font-bold text-white">
                    Rp {{ number_format(abs($summary['profit']), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Detail Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="flex items-center text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <h3 class="font-semibold">Detail Penjualan</h3>
                </div>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($salesData as $order)
                            <tr class="hover:bg-green-50/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span class="text-green-600 font-medium">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-green-700 text-right font-semibold">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    Tidak ada data penjualan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Costs Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                <div class="flex items-center text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    <h3 class="font-semibold">Detail Biaya & Pembelian</h3>
                </div>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($combinedCosts as $item)
                            <tr class="hover:bg-red-50/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $item->date->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <p class="text-gray-900 font-medium">{{ Str::limit($item->description, 30) }}</p>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ $item->type == 'expense' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-red-700 text-right font-semibold">
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    Tidak ada data biaya atau pembelian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .lg\:w-1\/4, .xl\:w-1\/5, aside {
        display: none !important;
    }
    .lg\:w-3\/4, .xl\:w-4\/5 {
        width: 100% !important;
    }
    nav, footer, .print\:hidden {
        display: none !important;
    }
}
</style>
@endsection