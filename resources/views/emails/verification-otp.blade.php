<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 480px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 32px 24px; text-align: center; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 16px 16px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">N'Kitchen</h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Verifikasi Email Anda</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Terima kasih telah mendaftar di N'Kitchen! Gunakan kode berikut untuk memverifikasi alamat email Anda:
                            </p>
                            
                            <!-- OTP Code -->
                            <div style="text-align: center; margin: 32px 0;">
                                <div style="display: inline-block; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 2px solid #f97316; border-radius: 12px; padding: 20px 40px;">
                                    <span style="font-size: 36px; font-weight: 700; letter-spacing: 12px; color: #ea580c; font-family: 'Courier New', monospace;">{{ $otp }}</span>
                                </div>
                            </div>
                            
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px; text-align: center;">
                                ⏱️ Kode ini berlaku selama <strong>10 menit</strong>
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; text-align: center;">
                                Jika Anda tidak merasa mendaftar, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f9fafb; border-radius: 0 0 16px 16px; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} N'Kitchen. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
