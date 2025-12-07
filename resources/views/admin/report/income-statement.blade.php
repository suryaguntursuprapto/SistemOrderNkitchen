@extends('layouts.report')

@section('report_content')
<div class="max-w-5xl mx-auto">
    
    <!-- Report Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Laporan Laba Rugi</h1>
                        <p class="text-orange-200 text-sm">N-Kitchen • Income Statement</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm text-orange-200">Periode:</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Period Selector & Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
            <!-- Quick Period Buttons -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Cepat</label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.income_statement.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-orange-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.income_statement.index', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-orange-50">
                        Bulan Lalu
                    </a>
                    <a href="{{ route('admin.income_statement.index', ['start_date' => now()->startOfQuarter()->format('Y-m-d'), 'end_date' => now()->endOfQuarter()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-orange-50">
                        Kuartal Ini
                    </a>
                    <a href="{{ route('admin.income_statement.index', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-orange-50">
                        Tahun Ini
                    </a>
                </div>
            </div>
            
            <!-- Custom Date Filter -->
            <form action="{{ route('admin.income_statement.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.income_statement.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Beban</p>
                    <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $netIncome >= 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
                    @if($netIncome >= 0)
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    @endif
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                    <p class="text-xl font-bold {{ $netIncome >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Statement Report -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <!-- Report Title Header -->
        <div class="px-6 py-4 text-center" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);">
            <h2 class="text-xl font-bold text-white">LAPORAN LABA RUGI</h2>
            <p class="text-orange-100 text-sm mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>

        <div class="p-6 space-y-8">
            <!-- PENDAPATAN Section -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">PENDAPATAN</h3>
                </div>
                <div class="bg-green-50 rounded-xl overflow-hidden border border-green-100">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-green-100">
                            @forelse($revenues as $revenue)
                            <tr class="hover:bg-green-100/50 transition-colors">
                                <td class="px-6 py-3 text-sm text-gray-500 w-24">{{ $revenue['code'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $revenue['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-green-700 text-right font-medium w-40">
                                    Rp {{ number_format($revenue['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm italic">
                                    Tidak ada data pendapatan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-green-100">
                            <tr class="font-bold">
                                <td colspan="2" class="px-6 py-3 text-sm text-green-800">Total Pendapatan</td>
                                <td class="px-6 py-3 text-sm text-green-800 text-right">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- BEBAN Section -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">BEBAN-BEBAN</h3>
                </div>
                <div class="bg-red-50 rounded-xl overflow-hidden border border-red-100">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-red-100">
                            @forelse($expenses as $expense)
                            <tr class="hover:bg-red-100/50 transition-colors">
                                <td class="px-6 py-3 text-sm text-gray-500 w-24">{{ $expense['code'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $expense['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-red-700 text-right font-medium w-40">
                                    Rp {{ number_format($expense['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm italic">
                                    Tidak ada data beban
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-red-100">
                            <tr class="font-bold">
                                <td colspan="2" class="px-6 py-3 text-sm text-red-800">Total Beban</td>
                                <td class="px-6 py-3 text-sm text-red-800 text-right">
                                    (Rp {{ number_format($totalExpenses, 0, ',', '.') }})
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- LABA/RUGI BERSIH -->
            <div class="border-t-2 border-gray-200 pt-6">
                <div class="rounded-xl p-6" style="background: linear-gradient(to right, {{ $netIncome >= 0 ? '#10b981, #059669' : '#ef4444, #dc2626' }});">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($netIncome >= 0)
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            @else
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <p class="text-white/80 text-sm font-medium uppercase">
                                    {{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                                </p>
                                <p class="text-white text-3xl font-bold">
                                    Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-sm">Profit Margin</p>
                            <p class="text-white text-2xl font-bold">
                                @if($totalRevenue > 0)
                                    {{ number_format(($netIncome / $totalRevenue) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
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
