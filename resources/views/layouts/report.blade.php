<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'N-Kitchen') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        /* Hide Alpine.js components until initialized */
        [x-cloak] { display: none !important; }
        
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary-gradient: linear-gradient(135deg, #f97316, #dc2626);
            --secondary-gradient: linear-gradient(135deg, #ea580c, #b91c1c);
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-strong: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s ease-in-out;
        }

        /* ===== SCROLLBAR STYLING ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
            transition: var(--transition-fast);
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
            box-shadow: var(--shadow-soft);
        }

        ::-webkit-scrollbar-corner {
            background: #f8fafc;
        }

        /* ===== GLOBAL TRANSITIONS ===== */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, 
                               fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        @keyframes scaleIn {
            from { 
                opacity: 0;
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% { transform: scale(0.9); }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ===== ANIMATION CLASSES ===== */
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-spin { animation: spin 1s linear infinite; }
        .animate-slide-in-right { animation: slideInRight 0.3s ease-out; }
        .animate-slide-out-right { animation: slideOutRight 0.3s ease-in; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-fade-out { animation: fadeOut 0.3s ease-in; }
        .animate-scale-in { animation: scaleIn 0.2s ease-out; }
        .animate-bounce-in { animation: bounceIn 0.5s ease-out; }

        /* ===== UTILITY CLASSES ===== */
        .custom-gradient {
            background: var(--primary-gradient);
        }

        .shadow-soft { box-shadow: var(--shadow-soft); }
        .shadow-medium { box-shadow: var(--shadow-medium); }
        .shadow-strong { box-shadow: var(--shadow-strong); }

        .transition-smooth { transition: var(--transition-smooth); }
        .transition-fast { transition: var(--transition-fast); }

        /* ===== BUTTON ENHANCEMENTS ===== */
        .logout-btn {
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }

        .logout-btn:hover .logout-icon {
            transform: rotate(12deg);
        }

        .logout-btn:hover {
            box-shadow: var(--shadow-strong);
            transform: translateY(-1px);
        }

        .logout-btn:active {
            transform: scale(0.95) translateY(0) !important;
        }

        /* Ripple effect */
        .logout-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .logout-btn:active::before {
            width: 100px;
            height: 100px;
        }

        /* ===== FLASH MESSAGES STYLING ===== */
        .flash-message {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .flash-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 12px 12px 0 0;
        }

        /* ===== LOADING OVERLAY ENHANCEMENTS ===== */
        .loading-overlay {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #f97316;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* ===== PAGE TRANSITION ===== */
        .page-transition {
            transition: opacity 0.3s ease-in-out;
        }

        .page-transition.fade-out {
            opacity: 0.7;
        }

        /* ===== RESPONSIVE ENHANCEMENTS ===== */
        @media (max-width: 640px) {
            .flash-message {
                margin: 0 1rem;
                max-width: calc(100vw - 2rem);
            }
        }

        /* ===== FOCUS STATES ===== */
        .focus-ring:focus {
            outline: none;
            ring: 2px;
            ring-color: #f97316;
            ring-opacity: 0.5;
        }

        /* ===== DARK MODE SUPPORT ===== */
        @media (prefers-color-scheme: dark) {
            ::-webkit-scrollbar-track {
                background: #1f2937;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-orange-50 min-h-screen page-transition">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('layouts.navbar')
        
        <!-- Flash Messages Container -->
        <div id="flash-messages" class="fixed top-20 right-4 z-50 space-y-3 pointer-events-none">
            @if(session('success'))
                <div class="flash-message relative bg-white/90 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-medium max-w-sm animate-slide-in-right pointer-events-auto">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-green-900 text-sm">Berhasil!</h4>
                            <p class="text-sm text-green-700 mt-1 leading-relaxed">{{ session('success') }}</p>
                        </div>
                        <button class="flash-close flex-shrink-0 text-green-400 hover:text-green-600 p-1 rounded transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="flash-message relative bg-white/90 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-medium max-w-sm animate-slide-in-right pointer-events-auto">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-red-900 text-sm">Oops!</h4>
                            <p class="text-sm text-red-700 mt-1 leading-relaxed">{{ session('error') }}</p>
                        </div>
                        <button class="flash-close flex-shrink-0 text-red-400 hover:text-red-600 p-1 rounded transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="flash-message relative bg-white/90 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-xl shadow-medium max-w-sm animate-slide-in-right pointer-events-auto">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-yellow-900 text-sm">Perhatian!</h4>
                            <p class="text-sm text-yellow-700 mt-1 leading-relaxed">{{ session('warning') }}</p>
                        </div>
                        <button class="flash-close flex-shrink-0 text-yellow-400 hover:text-yellow-600 p-1 rounded transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('info'))
                <div class="flash-message relative bg-white/90 border border-blue-200 text-blue-800 px-6 py-4 rounded-xl shadow-medium max-w-sm animate-slide-in-right pointer-events-auto">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-blue-900 text-sm">Informasi</h4>
                            <p class="text-sm text-blue-700 mt-1 leading-relaxed">{{ session('info') }}</p>
                        </div>
                        <button class="flash-close flex-shrink-0 text-blue-400 hover:text-blue-600 p-1 rounded transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        
       <main class="flex-1 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <aside class="lg:w-1/4 xl:w-1/5 flex-shrink-0">
                        @include('layouts.partials.report-sidebar')
                    </aside>
                    
                    <div class="lg:w-3/4 xl:w-4/5">
                        @yield('report_content')
                    </div>
                    
                </div>
            </div>
        </main>
        @include('layouts.footer')
    </div>

    <!-- Enhanced Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl p-8 flex flex-col items-center space-y-4 shadow-strong animate-scale-in max-w-xs mx-4">
            <div class="loading-spinner w-12 h-12"></div>
            <div class="text-center">
                <h3 class="text-gray-900 font-semibold text-lg">Loading...</h3>
                <p class="text-gray-600 text-sm mt-1">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        class AppController {
            constructor() {
                this.initFlashMessages();
                this.initLoadingSystem();
                this.initPageTransitions();
                this.initAccessibility();
            }

            // Flash Messages System
            initFlashMessages() {
                const flashMessages = document.querySelectorAll('.flash-message');
                
                flashMessages.forEach(message => {
                    // Auto-hide after 6 seconds
                    const autoHideTimer = setTimeout(() => {
                        this.hideFlashMessage(message);
                    }, 6000);

                    // Close button functionality
                    const closeButton = message.querySelector('.flash-close');
                    if (closeButton) {
                        closeButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            clearTimeout(autoHideTimer);
                            this.hideFlashMessage(message);
                        });
                    }

                    // Pause auto-hide on hover
                    message.addEventListener('mouseenter', () => clearTimeout(autoHideTimer));
                    message.addEventListener('mouseleave', () => {
                        setTimeout(() => this.hideFlashMessage(message), 2000);
                    });
                });
            }

            hideFlashMessage(message) {
                if (!message || message.classList.contains('animate-slide-out-right')) return;
                
                message.classList.remove('animate-slide-in-right');
                message.classList.add('animate-slide-out-right');
                
                setTimeout(() => {
                    if (message.parentNode) {
                        message.remove();
                    }
                }, 300);
            }

            // Loading System
            initLoadingSystem() {
                // Global loading functions
                window.showLoading = () => {
                    const overlay = document.getElementById('loading-overlay');
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                };

                window.hideLoading = () => {
                    const overlay = document.getElementById('loading-overlay');
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    document.body.style.overflow = '';
                };

                // Show loading on form submissions
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', (e) => {
                        if (form.method.toLowerCase() !== 'get' && !form.hasAttribute('data-no-loading')) {
                            setTimeout(window.showLoading, 100);
                        }
                    });
                });

                // Hide loading when page loads
                window.addEventListener('load', window.hideLoading);
                window.addEventListener('pageshow', window.hideLoading);

                // Prevent loading on back/forward navigation
                window.addEventListener('beforeunload', window.hideLoading);
            }

            // Page Transitions
            initPageTransitions() {
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href^="/"]');
                    
                    if (link && 
                        !link.hasAttribute('target') && 
                        !link.href.includes('logout') &&
                        !link.href.includes('#') &&
                        !link.hasAttribute('data-no-transition')) {
                        
                        e.preventDefault();
                        
                        // Add fade out effect
                        document.body.classList.add('fade-out');
                        
                        setTimeout(() => {
                            window.location.href = link.href;
                        }, 150);
                    }
                });
            }

            // Accessibility Enhancements
            initAccessibility() {
                // ESC key to close flash messages
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        const flashMessages = document.querySelectorAll('.flash-message');
                        flashMessages.forEach(message => this.hideFlashMessage(message));
                    }
                });

                // Focus management for flash messages
                document.querySelectorAll('.flash-close').forEach(button => {
                    button.setAttribute('aria-label', 'Tutup pesan');
                });

                // Enhanced keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        document.body.classList.add('user-is-tabbing');
                    }
                });

                document.addEventListener('mousedown', () => {
                    document.body.classList.remove('user-is-tabbing');
                });
            }

            // Utility Methods
            static createToast(type, title, message) {
                const toastContainer = document.getElementById('flash-messages');
                if (!toastContainer) return;

                const colors = {
                    success: 'green',
                    error: 'red', 
                    warning: 'yellow',
                    info: 'blue'
                };

                const color = colors[type] || 'blue';
                const toast = document.createElement('div');
                toast.className = `flash-message relative bg-white/90 border border-${color}-200 text-${color}-800 px-6 py-4 rounded-xl shadow-medium max-w-sm animate-slide-in-right pointer-events-auto`;
                
                toast.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 bg-${color}-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-${color}-900 text-sm">${title}</h4>
                            <p class="text-sm text-${color}-700 mt-1 leading-relaxed">${message}</p>
                        </div>
                        <button class="flash-close flex-shrink-0 text-${color}-400 hover:text-${color}-600 p-1 rounded transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                toastContainer.appendChild(toast);

                // Initialize the new toast
                const app = new AppController();
                app.initFlashMessages();

                return toast;
            }
        }

        // Initialize app when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new AppController();
        });

        // Make toast creation available globally
        window.createToast = AppController.createToast;
    </script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>