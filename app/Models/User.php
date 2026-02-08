<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationOtp;

class User extends Authenticatable implements MustVerifyEmail 
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'google_id',
        'email_otp',
        'email_otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Generate and send email OTP
     */
    public function generateEmailOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($this->email)->send(new EmailVerificationOtp($this, $otp));

        return $otp;
    }

    /**
     * Verify email OTP code
     */
    public function verifyEmailOtp(string $code): bool
    {
        if ($this->email_otp !== $code) {
            return false;
        }

        if ($this->email_otp_expires_at && $this->email_otp_expires_at->isPast()) {
            return false;
        }

        $this->update([
            'email_verified_at' => now(),
            'email_otp' => null,
            'email_otp_expires_at' => null,
        ]);

        return true;
    }
}