@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">    
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all hover:scale-[1.01]">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-8 text-center">
                <div class="mx-auto h-20 w-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mb-4 animate-pulse">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Verifikasi Email</h2>
                <p class="mt-2 text-orange-100 text-sm">Masukkan kode 6 digit yang dikirim ke</p>
                <p class="text-white font-medium">{{ Auth::user()->email }}</p>
            </div>

            <!-- Content -->
            <div class="px-8 py-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm p-4 rounded-xl flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm p-4 rounded-xl flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @error('otp')
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm p-4 rounded-xl">
                        {{ $message }}
                    </div>
                @enderror

                <!-- OTP Form -->
                <form method="POST" action="{{ route('verification.verify-otp') }}" id="otp-form">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 text-center mb-4">Kode Verifikasi</label>
                        <div class="flex justify-center gap-2" id="otp-container">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" 
                                       name="otp[]" 
                                       maxlength="1" 
                                       pattern="[0-9]"
                                       inputmode="numeric"
                                       class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-200 outline-none"
                                       autocomplete="off"
                                       required>
                            @endfor
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 px-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        Verifikasi Email
                    </button>
                </form>

                <!-- Resend Section -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 mb-3">Tidak menerima kode?</p>
                    <form method="POST" action="{{ route('verification.resend-otp') }}" id="resend-form">
                        @csrf
                        <button type="submit" 
                                id="resend-btn"
                                class="text-orange-600 font-semibold hover:text-orange-700 focus:outline-none disabled:text-gray-400 disabled:cursor-not-allowed transition-colors">
                            Kirim Ulang Kode
                        </button>
                    </form>
                    <p id="countdown" class="text-xs text-gray-400 mt-2 hidden">
                        Kirim ulang dalam <span id="timer">60</span> detik
                    </p>
                </div>

                <!-- Logout -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            ← Kembali ke Login
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info -->
        <p class="mt-6 text-center text-sm text-orange-700">
            Kode berlaku selama 10 menit
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const form = document.getElementById('otp-form');
    
    // Auto-focus first input
    inputs[0].focus();
    
    inputs.forEach((input, index) => {
        // Handle input
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d*$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Move to next input
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            
            // Auto-submit when all filled
            if (index === inputs.length - 1 && value) {
                const allFilled = Array.from(inputs).every(inp => inp.value);
                if (allFilled) {
                    setTimeout(() => form.submit(), 100);
                }
            }
        });
        
        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            
            pasteData.split('').forEach((char, i) => {
                if (inputs[i]) {
                    inputs[i].value = char;
                }
            });
            
            if (pasteData.length === 6) {
                setTimeout(() => form.submit(), 100);
            }
        });
    });
    
    // Countdown timer after resend
    @if(session('otp_sent'))
    startCountdown();
    @endif
    
    // Handle resend form submit - just start countdown
    document.getElementById('resend-form').addEventListener('submit', function() {
        startCountdown();
    });
});

function startCountdown() {
    const btn = document.getElementById('resend-btn');
    const countdownEl = document.getElementById('countdown');
    const timerEl = document.getElementById('timer');
    let seconds = 60;
    
    btn.disabled = true;
    countdownEl.classList.remove('hidden');
    
    const interval = setInterval(function() {
        seconds--;
        timerEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(interval);
            btn.disabled = false;
            countdownEl.classList.add('hidden');
        }
    }, 1000);
}
</script>

<style>
.otp-input:focus {
    transform: scale(1.05);
}
</style>
@endsection