@extends('layouts.report')

@section('report_content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Laba Rugi</h1>
        <p class="text-gray-600 mt-1">Ringkasan pendapatan, beban, dan laba/rugi bersih.</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <form action="{{ route('admin.income_statement.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" 
                       value="{{ $startDate }}" 
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" 
                       value="{{ $endDate }}" 
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Filter
                </button>
                <a href="{{ route('admin.income_statement.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4" style="background: linear-gradient(to right, #f97316, #ea580c);">
            <h2 class="text-xl font-bold text-white text-center">LAPORAN LABA RUGI</h2>
            <p class="text-orange-100 text-center text-sm mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>

        <div class="p-6 space-y-6">
            <!-- PENDAPATAN Section -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    PENDAPATAN
                </h3>
                <div class="bg-green-50 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-green-100">
                            @forelse($revenues as $revenue)
                            <tr class="hover:bg-green-100/50">
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $revenue['code'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $revenue['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-right font-medium">
                                    Rp {{ number_format($revenue['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">
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
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </span>
                    BEBAN-BEBAN
                </h3>
                <div class="bg-red-50 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-red-100">
                            @forelse($expenses as $expense)
                            <tr class="hover:bg-red-100/50">
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $expense['code'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $expense['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-right font-medium">
                                    Rp {{ number_format($expense['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">
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
            <div class="border-t-2 border-gray-300 pt-6">
                <div class="rounded-xl p-6" style="background: linear-gradient(to right, {{ $netIncome >= 0 ? '#22c55e, #16a34a' : '#ef4444, #dc2626' }});">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($netIncome >= 0)
                            <svg class="w-10 h-10 text-white" style="opacity: 0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            @else
                            <svg class="w-10 h-10 text-white" style="opacity: 0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            @endif
                            <div class="ml-4">
                                <p class="text-white text-sm font-medium uppercase" style="opacity: 0.9;">
                                    {{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                                </p>
                                <p class="text-white text-3xl font-bold">
                                    Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white text-sm" style="opacity: 0.9;">Margin</p>
                            <p class="text-white text-xl font-bold">
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-gray-500 text-sm">Total Beban</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-gray-500 text-sm">{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                    <p class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
