@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Report Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Neraca Saldo</h1>
                        <p class="text-purple-200 text-sm">N-Kitchen • Trial Balance</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm text-purple-200">Periode:</p>
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
                    <a href="{{ route('admin.trial_balance.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-purple-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.trial_balance.index', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-purple-50">
                        Bulan Lalu
                    </a>
                    <a href="{{ route('admin.trial_balance.index', ['start_date' => now()->startOfQuarter()->format('Y-m-d'), 'end_date' => now()->endOfQuarter()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-purple-50">
                        Kuartal Ini
                    </a>
                    <a href="{{ route('admin.trial_balance.index', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-purple-50">
                        Tahun Ini
                    </a>
                </div>
            </div>
            
            <!-- Custom Date Filter -->
            <form action="{{ route('admin.trial_balance.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.trial_balance.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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

    <!-- Balance Status Card -->
    @if(count($trialBalance) > 0)
    <div class="mb-6">
        @if($totalDebit == $totalCredit)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-green-800 font-bold">✓ Balance</h3>
                    <p class="text-green-700 text-sm">Total Debit dan Kredit seimbang. Neraca saldo Anda sudah benar.</p>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-green-800 font-bold text-xl">Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-red-800 font-bold">⚠ Tidak Balance</h3>
                    <p class="text-red-700 text-sm">Terdapat selisih antara Debit dan Kredit. Silakan periksa jurnal Anda.</p>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-red-800 font-bold">Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Trial Balance Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-28">
                            Kode Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-28">
                            Tipe
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider w-40">
                            Debit (Rp)
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider w-40">
                            Kredit (Rp)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @php
                        $currentType = '';
                    @endphp
                    @forelse ($trialBalance as $account)
                        @if($currentType != $account['type'])
                            @php $currentType = $account['type']; @endphp
                            <!-- Category Header -->
                            <tr class="bg-gray-50">
                                <td colspan="5" class="px-6 py-3">
                                    <span class="font-bold text-gray-700 uppercase text-sm">
                                        @if($account['type'] == 'aset') 📦 Aset
                                        @elseif($account['type'] == 'kewajiban') 📋 Kewajiban
                                        @elseif($account['type'] == 'modal') 🏛 Modal
                                        @elseif($account['type'] == 'pendapatan') 💰 Pendapatan
                                        @elseif($account['type'] == 'beban') 💸 Beban
                                        @else {{ ucfirst($account['type']) }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endif
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-purple-600">
                                {{ $account['code'] }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-800">
                                {{ $account['name'] }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    @if($account['type'] == 'aset') bg-blue-100 text-blue-700
                                    @elseif($account['type'] == 'kewajiban') bg-red-100 text-red-700
                                    @elseif($account['type'] == 'modal') bg-purple-100 text-purple-700
                                    @elseif($account['type'] == 'pendapatan') bg-green-100 text-green-700
                                    @elseif($account['type'] == 'beban') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($account['type']) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $account['debit'] > 0 ? 'text-green-700 font-medium' : 'text-gray-400' }}">
                                {{ $account['debit'] > 0 ? number_format($account['debit'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $account['credit'] > 0 ? 'text-red-700 font-medium' : 'text-gray-400' }}">
                                {{ $account['credit'] > 0 ? number_format($account['credit'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Tidak ada data akun</p>
                                    <p class="text-gray-400 text-sm">pada rentang tanggal yang dipilih</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($trialBalance) > 0)
                <tfoot style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
                    <tr class="font-bold">
                        <td colspan="3" class="px-6 py-4 text-sm text-gray-900 uppercase">
                            TOTAL
                        </td>
                        <td class="px-6 py-4 text-lg text-green-700 text-right">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-lg text-red-700 text-right">
                            Rp {{ number_format($totalCredit, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
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
