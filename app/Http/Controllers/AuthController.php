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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        // 🚀 PENTING: Kirim email verifikasi
        event(new Registered($user));

        Auth::login($user);

        return redirect('/customer/dashboard');
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
}