@auth
<nav class="bg-gray-100 backdrop-blur-sm shadow-lg sticky top-0 z-50 border-b border-orange-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" 
                    style="background: linear-gradient(to right,hsl(25, 95.00%, 53.10%), #dc2626);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl text-orange-500">
                            N-Kitchen Pempek
                        </h1>
                        <p class="text-xs text-gray-500 -mt-1">Cita Rasa Palembang</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                @if(auth()->user()->isAdmin())
                <div class="hidden lg:flex space-x-1 ml-8">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('admin.dashboard') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.menu.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('admin.menu.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('admin.menu.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Menu
                    </a>
                    <a href="{{ route('admin.order.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('admin.order.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('admin.order.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Pesanan
                    </a>
                    <a href="{{ route('admin.message.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('admin.message.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('admin.message.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Pesan
                    </a>
                    <a href="{{ route('admin.report.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.report.*') || Request::routeIs('admin.journal.*') || Request::routeIs('admin.ledger.*') || Request::routeIs('admin.coa.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2z"></path>
                        </svg>
                        Laporan
                    </a>
                </div>
                @else
                <div class="hidden lg:flex space-x-1 ml-8">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('customer.dashboard') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('customer.dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('customer.menu.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('customer.menu.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('customer.menu.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Menu
                    </a>
                    <a href="{{ route('customer.order.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('customer.order.index') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('customer.order.index') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Pemesanan
                    </a>
                    <a href="{{ route('customer.orders') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('customer.orders') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('customer.orders') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat
                    </a>
                    <a href="{{ route('customer.chat.index') }}" 
                       class="group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ Request::routeIs('customer.chat.*') || Request::routeIs('customer.message.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Request::routeIs('customer.chat.*') || Request::routeIs('customer.message.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat
                    </a>
                </div>
                @endif
            </div>

            <!-- User Section -->
            <div class="flex items-center space-x-4">
                <!-- User Info -->
                <div class="hidden md:flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: linear-gradient(to right, #fb923c, #f87171);">
                        <span class="text-sm font-medium text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->isAdmin() ? 'Administrator' : 'Customer' }}
                        </p>
                    </div>
                </div>

               <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="logout-btn group inline-flex items-center px-4 py-2 text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl"
                        style="background: linear-gradient(to right, #ef4444, #dc2626);"
                        onmouseover="this.style.background='linear-gradient(to right, #dc2626, #b91c1c)'; this.style.transform='scale(1.05)'"
                        onmouseout="this.style.background='linear-gradient(to right, #ef4444, #dc2626)'; this.style.transform='scale(1)'"
                        onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <svg class="logout-icon w-4 h-4 mr-2 transition-transform duration-300" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="hidden sm:inline">Logout</span>
                </button>
                </form>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button type="button" id="mobile-menu-button" 
                            class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 hover:text-orange-600 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden pb-4">
            <div class="space-y-2 px-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.dashboard') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.menu.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.menu.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Kelola Menu
                    </a>
                    <a href="{{ route('admin.order.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.order.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Kelola Pesanan
                    </a>
                    <a href="{{ route('admin.message.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.message.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Pesan
                    </a>
                    <a href="{{ route('admin.report.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('admin.report.*') || Request::routeIs('admin.journal.*') || Request::routeIs('admin.ledger.*') || Request::routeIs('admin.coa.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2z"></path>
                        </svg>
                        Laporan
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('customer.dashboard') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('customer.menu.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('customer.menu.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Menu
                    </a>
                    <a href="{{ route('customer.order.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('customer.order.index') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Pemesanan
                    </a>
                    <a href="{{ route('customer.orders') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('customer.orders') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Pemesanan
                    </a>
                    <a href="{{ route('customer.chat.index') }}" 
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium {{ Request::routeIs('customer.chat.*') || Request::routeIs('customer.message.*') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }} transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat
                    </a>
                @endif

                <!-- User Info Mobile -->
                <div class="md:hidden mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center px-4 py-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background: linear-gradient(to right, #fb923c, #f87171);">
                            <span class="text-sm font-medium text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-base font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Customer' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
});
</script>
@endauth