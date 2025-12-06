<nav class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
    
    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">
        Input Transaksi
    </h3>
    <div class="space-y-1">
        
        <a href="{{ route('admin.purchases.index') }}" 
           class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.purchases.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
            <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.purchases.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
           Pembelian
        </a>
        
        <a href="{{ route('admin.expense.index') }}" 
           class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.expense.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
            <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.expense.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
            </svg>
           Biaya (Expense)
        </a>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-200">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">
            Laporan Akuntansi
        </h3>
        <div class="space-y-1">
            <a href="{{ route('admin.report.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.report.index') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.report.index') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
               Ringkasan Keuangan
            </a>
            
            <a href="{{ route('admin.journal.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.journal.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.journal.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
               Jurnal Umum
            </a>

            <a href="{{ route('admin.ledger.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.ledger.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.ledger.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M6 13H4"></path>
                </svg>
               Buku Besar
            </a>

            <a href="{{ route('admin.trial_balance.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.trial_balance.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.trial_balance.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                </svg>
               Neraca Saldo
            </a>

            <a href="{{ route('admin.income_statement.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.income_statement.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.income_statement.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
               Laporan Laba Rugi
            </a>

            <a href="{{ route('admin.chart_of_accounts.index') }}" 
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('admin.chart_of_accounts.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::routeIs('admin.chart_of_accounts.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2z"></path>

                </svg>
               Bagan Akun (COA)
            </a>
        </div>
    </div>
</nav>