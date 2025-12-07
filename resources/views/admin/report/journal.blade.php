@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Report Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Jurnal Umum</h1>
                        <p class="text-blue-200 text-sm">N-Kitchen • General Journal</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm text-blue-200">Periode:</p>
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
                    <a href="{{ route('admin.journal.index', ['start_date' => now()->startOfDay()->format('Y-m-d'), 'end_date' => now()->endOfDay()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border {{ $startDate == now()->format('Y-m-d') && $endDate == now()->format('Y-m-d') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-blue-50' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('admin.journal.index', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-blue-50">
                        Minggu Ini
                    </a>
                    <a href="{{ route('admin.journal.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-blue-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.journal.index', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" 
                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 border-gray-300 hover:bg-blue-50">
                        Tahun Ini
                    </a>
                </div>
            </div>
            
            <!-- Custom Date Filter -->
            <form action="{{ route('admin.journal.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.journal.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jurnal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $journals->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Debit</p>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($journals->sum(fn($j) => $j->transactions->sum('debit')), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Kredit</p>
                    <p class="text-xl font-bold text-red-600">Rp {{ number_format($journals->sum(fn($j) => $j->transactions->sum('credit')), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Journal Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-32">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Keterangan & Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-28">
                            Ref.
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
                    @forelse ($journals as $journal)
                        <!-- Journal Header Row -->
                        <tr class="bg-blue-50/50 border-l-4 border-blue-500">
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900 font-semibold" colspan="2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $journal->description }}
                                </div>
                            </td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        
                        <!-- Transaction Rows -->
                        @foreach ($journal->transactions as $tx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm {{ $tx->credit > 0 ? 'pl-12' : 'pl-6' }}">
                                <span class="inline-flex items-center">
                                    @if($tx->debit > 0)
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    @else
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    @endif
                                    <span class="text-gray-500 mr-2">[{{ $tx->chartOfAccount->code }}]</span>
                                    <span class="text-gray-800">{{ $tx->chartOfAccount->name }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                @if ($journal->referenceable_type === 'App\Models\Order')
                                    <a href="{{ route('admin.order.show', $journal->referenceable_id) }}" class="text-blue-600 hover:underline">
                                        {{ $journal->referenceable->order_number ?? '-' }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $tx->debit > 0 ? 'text-green-700 font-medium' : 'text-gray-400' }}">
                                {{ $tx->debit > 0 ? number_format($tx->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right {{ $tx->credit > 0 ? 'text-red-700 font-medium' : 'text-gray-400' }}">
                                {{ $tx->credit > 0 ? number_format($tx->credit, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">Tidak ada data jurnal</p>
                                    <p class="text-gray-400 text-sm">pada rentang tanggal yang dipilih</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if ($journals->hasPages())
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
                {!! $journals->appends(request()->query())->links() !!}
            </div>
        </div>
    @endif
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