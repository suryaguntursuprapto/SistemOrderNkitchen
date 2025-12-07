@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Report Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Buku Besar</h1>
                        <p class="text-green-200 text-sm">N-Kitchen • General Ledger</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm text-green-200">Periode:</p>
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
                    <a href="{{ route('admin.ledger.index', ['start_date' => now()->startOfDay()->format('Y-m-d'), 'end_date' => now()->endOfDay()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-green-50">
                        Hari Ini
                    </a>
                    <a href="{{ route('admin.ledger.index', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-green-50">
                        Minggu Ini
                    </a>
                    <a href="{{ route('admin.ledger.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-green-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.ledger.index', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-green-50">
                        Tahun Ini
                    </a>
                </div>
            </div>
            
            <!-- Custom Date Filter -->
            <form action="{{ route('admin.ledger.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.ledger.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition">
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

    <!-- Accounts Ledger -->
    <div class="space-y-6">
        @forelse ($accounts as $account)
            @php
                $balance = 0;
            @endphp
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <!-- Account Header -->
                <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                @if($account->type == 'aset') bg-blue-100
                                @elseif($account->type == 'kewajiban') bg-red-100
                                @elseif($account->type == 'modal') bg-purple-100
                                @elseif($account->type == 'pendapatan') bg-green-100
                                @elseif($account->type == 'beban') bg-orange-100
                                @else bg-gray-100
                                @endif">
                                <span class="text-lg font-bold 
                                    @if($account->type == 'aset') text-blue-600
                                    @elseif($account->type == 'kewajiban') text-red-600
                                    @elseif($account->type == 'modal') text-purple-600
                                    @elseif($account->type == 'pendapatan') text-green-600
                                    @elseif($account->type == 'beban') text-orange-600
                                    @else text-gray-600
                                    @endif">{{ substr($account->code, 0, 1) }}</span>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">{{ $account->name }}</h2>
                                <div class="flex items-center space-x-3 text-sm text-gray-500">
                                    <span>Kode: {{ $account->code }}</span>
                                    <span>•</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                        @if($account->type == 'aset') bg-blue-100 text-blue-700
                                        @elseif($account->type == 'kewajiban') bg-red-100 text-red-700
                                        @elseif($account->type == 'modal') bg-purple-100 text-purple-700
                                        @elseif($account->type == 'pendapatan') bg-green-100 text-green-700
                                        @elseif($account->type == 'beban') bg-orange-100 text-orange-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">{{ ucfirst($account->type) }}</span>
                                    <span>•</span>
                                    <span>Saldo Normal: {{ $account->normal_balance }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transactions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase">Debit (Rp)</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase">Kredit (Rp)</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @if($account->journalTransactions->count() > 0)
                                <!-- Opening Balance Row -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-3 text-sm text-gray-700 italic">Saldo Awal</td>
                                    <td class="px-6 py-3 text-sm text-gray-500 text-right">-</td>
                                    <td class="px-6 py-3 text-sm text-gray-500 text-right">-</td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">0</td>
                                </tr>
                            @endif
                            @foreach ($account->journalTransactions as $tx)
                                @php
                                    if ($account->normal_balance == 'Debit') {
                                        $balance += ($tx->debit - $tx->credit);
                                    } else {
                                        $balance += ($tx->credit - $tx->debit);
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($tx->journal->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $tx->journal->description }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $tx->debit > 0 ? 'text-green-700 font-medium' : 'text-gray-400' }}">
                                        {{ $tx->debit > 0 ? number_format($tx->debit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $tx->credit > 0 ? 'text-red-700 font-medium' : 'text-gray-400' }}">
                                        {{ $tx->credit > 0 ? number_format($tx->credit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-right {{ $balance >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                                        {{ number_format($balance, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">
                                    Saldo Akhir
                                </td>
                                <td class="px-6 py-4 text-right text-lg font-bold {{ $balance >= 0 ? 'text-green-700' : 'text-red-600' }}">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M6 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada aktivitas akun</p>
                    <p class="text-gray-400 text-sm">pada rentang tanggal yang dipilih</p>
                </div>
            </div>
        @endforelse
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