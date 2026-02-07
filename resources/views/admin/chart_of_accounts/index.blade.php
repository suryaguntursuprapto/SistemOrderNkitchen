@extends('layouts.report')

@section('report_content')
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Bagan Akun (COA)</h1>
                        <p class="text-purple-200 text-sm">Chart of Accounts</p>
                    </div>
                </div>
                <a href="{{ route('admin.chart_of_accounts.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-xl text-sm font-semibold shadow-lg hover:bg-purple-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Akun Baru
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

    <!-- Accounts Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">
                            No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-24">
                            Kode
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Akun
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-28">
                            Tipe
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-28">
                            Saldo Normal
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider w-36">
                            Saldo Saat Ini
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider w-40">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-purple-50/50 transition-colors {{ $account->parent_id ? 'bg-gray-50/50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration + $accounts->firstItem() - 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600 font-bold">
                                {{ $account->code }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                @if($account->parent_id)
                                    <span class="text-gray-400 mr-2">↳</span>
                                @endif
                                {{ $account->name }}
                                @if($account->parent)
                                    <span class="text-xs text-gray-400 ml-2">(Sub dari: {{ $account->parent->name }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    @if($account->type == 'Asset') bg-blue-100 text-blue-700
                                    @elseif($account->type == 'Liability') bg-red-100 text-red-700
                                    @elseif($account->type == 'Equity') bg-purple-100 text-purple-700
                                    @elseif($account->type == 'Revenue') bg-green-100 text-green-700
                                    @elseif($account->type == 'Expense') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($account->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100">
                                    {{ $account->normal_balance }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                @php
                                    $balance = $account->opening_balance;
                                    $total_debits = $account->journal_transactions_sum_debit ?? 0;
                                    $total_credits = $account->journal_transactions_sum_credit ?? 0;

                                    if ($account->normal_balance == 'Debit') {
                                        $current_balance = $balance + $total_debits - $total_credits;
                                    } else {
                                        $current_balance = $balance - $total_debits + $total_credits;
                                    }
                                @endphp
                                <span class="{{ $current_balance >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                                    Rp {{ number_format($current_balance, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <div class="inline-flex gap-1">
                                    <a href="{{ route('admin.chart_of_accounts.show', $account->id) }}" 
                                       class="inline-flex items-center px-2 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.chart_of_accounts.edit', $account->id) }}" 
                                       class="inline-flex items-center px-2 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium hover:bg-purple-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.chart_of_accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data akun</p>
                                    <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Akun Baru" untuk menambahkan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($accounts->hasPages())
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
                {!! $accounts->links() !!}
            </div>
        </div>
    @endif
</div>
@endsection