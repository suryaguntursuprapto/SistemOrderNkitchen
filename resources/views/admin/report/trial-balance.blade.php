@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Neraca Saldo</h1>
        <p class="text-gray-600 mt-1">Daftar saldo semua akun pada periode tertentu.</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <form action="{{ route('admin.trial_balance.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
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
                <a href="{{ route('admin.trial_balance.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(to right, #16a34a, #15803d);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Kode Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Nama Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Tipe
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-white uppercase tracking-wider">
                            Debit (Rp)
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-white uppercase tracking-wider">
                            Kredit (Rp)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($trialBalance as $account)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $account['code'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $account['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($account['type'] == 'aset') bg-blue-100 text-blue-800
                                    @elseif($account['type'] == 'kewajiban') bg-red-100 text-red-800
                                    @elseif($account['type'] == 'modal') bg-purple-100 text-purple-800
                                    @elseif($account['type'] == 'pendapatan') bg-green-100 text-green-800
                                    @elseif($account['type'] == 'beban') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($account['type']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $account['debit'] > 0 ? 'Rp ' . number_format($account['debit'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $account['credit'] > 0 ? 'Rp ' . number_format($account['credit'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm">Tidak ada data akun pada rentang tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($trialBalance) > 0)
                <tfoot class="bg-gray-100">
                    <tr class="font-bold">
                        <td colspan="3" class="px-6 py-4 text-sm text-gray-900 uppercase">
                            Total
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">
                            Rp {{ number_format($totalCredit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($totalDebit == $totalCredit)
                    <tr>
                        <td colspan="5" class="px-6 py-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Balance! Debit = Kredit
                            </span>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="5" class="px-6 py-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Tidak Balance! Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endif
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
