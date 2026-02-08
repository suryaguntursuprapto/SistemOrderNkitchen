<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered; // <-- PENTING: Untuk Verifikasi Email
use Illuminate\Support\Facades\Password; // <-- PENTING: Untuk Reset Password
use Illuminate\Support\Str; // <-- PENTING: Helper String

class AuthController extends Controller
{
    // --- LOGIN ---

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns', // Validate email format AND check DNS
                'max:255',
            ],
            'password' => ['required', 'string', 'min:1'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Fitur "Remember Me" (Checkbox)
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/customer/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    // --- REGISTER ---

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/', // Only letters and spaces
            ],
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns', // Validate email format AND check DNS
                'max:255', 
                'unique:users,email',
                'not_regex:/\+/', // Prevent + alias emails (spam prevention)
            ],
            'password' => [
                'required', 
                'string', 
                'min:8',
                'max:128',
                'confirmed',
                'regex:/[a-z]/', // Must contain lowercase
                'regex:/[A-Z]/', // Must contain uppercase
                'regex:/[0-9]/', // Must contain number
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.not_regex' => 'Email tidak boleh menggunakan karakter +.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 128 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        // Generate username dari email (bagian sebelum @)
        $emailParts = explode('@', $validated['email']);
        $baseUsername = $emailParts[0];
        $username = $baseUsername;
        $counter = 1;
        
        // Pastikan username unik
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        // Kirim OTP verifikasi email
        $user->generateEmailOtp();

        Auth::login($user);

        return redirect()->route('verification.notice')->with('otp_sent', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // --- FITUR LUPA PASSWORD (BARU) ---

    /**
     * 1. Tampilkan form input email untuk reset.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * 2. Proses kirim link reset ke email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kirim link
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda!');
        }

        return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }

    /**
     * 3. Tampilkan form reset password baru (setelah klik link di email).
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * 4. Proses simpan password baru.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                
                // Opsional: Otomatis verifikasi email jika berhasil reset password
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password Anda telah berhasil direset! Silakan login.');
        }

        return back()->withErrors(['email' => 'Token reset password tidak valid atau telah kedaluwarsa.']);
    }

    // --- FITUR VERIFIKASI OTP EMAIL ---

    /**
     * Verifikasi kode OTP email
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:6',
            'otp.*' => 'required|digits:1',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $otp = implode('', $request->otp);
        $user = Auth::user();

        if ($user->verifyEmailOtp($otp)) {
            return redirect('/customer/dashboard')->with('success', 'Email berhasil diverifikasi!');
        }

        return back()->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa.');
    }

    /**
     * Kirim ulang kode OTP
     */
    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        
        // Rate limiting: max 3 resends per hour
        $lastSent = $user->email_otp_expires_at?->subMinutes(10);
        if ($lastSent && $lastSent->diffInMinutes(now()) < 1) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tunggu 1 menit sebelum mengirim ulang.'], 429);
            }
            return redirect()->route('verification.notice')->with('error', 'Tunggu 1 menit sebelum mengirim ulang.');
        }

        $user->generateEmailOtp();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim ke email Anda.']);
        }

        return redirect()->route('verification.notice')->with('success', 'Kode OTP baru telah dikirim ke email Anda.')->with('otp_sent', true);
    }
}