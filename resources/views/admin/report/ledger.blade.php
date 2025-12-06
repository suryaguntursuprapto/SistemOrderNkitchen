@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Buku Besar</h1>
        <p class="text-gray-600 mt-1">Daftar transaksi per akun beserta saldo berjalan.</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <form action="{{ route('admin.ledger.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
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
                <a href="{{ route('admin.ledger.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    <div class="space-y-8">
        @forelse ($accounts as $account)
            @php
                $balance = 0;
            @endphp
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $account->name }}</h2>
                    <p class="text-sm text-gray-600">Kode Akun: {{ $account->code }} | Saldo Normal: {{ $account->normal_balance }}</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Kredit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($account->journalTransactions as $tx)
                                @php
                                    if ($account->normal_balance == 'Debit') {
                                        $balance += ($tx->debit - $tx->credit);
                                    } else {
                                        $balance += ($tx->credit - $tx->debit);
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($tx->journal->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $tx->journal->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right">
                                        {{ $tx->debit > 0 ? 'Rp ' . number_format($tx->debit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right">
                                        {{ $tx->credit > 0 ? 'Rp ' . number_format($tx->credit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-right">
                                        Rp {{ number_format($balance, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">
                                    Saldo Akhir
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M6 13H4"></path>
                </svg>
                <p class="mt-2 text-sm">Tidak ada aktivitas akun pada rentang tanggal ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection