<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dikirim</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff7ed;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px; text-align: center; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 16px 16px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">N'Kitchen</h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Notifikasi Pengiriman</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <!-- Shipping Badge -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="display: inline-block; background-color: #dbeafe; border-radius: 50%; padding: 16px;">
                                    <span style="font-size: 32px;">🚚</span>
                                </div>
                                <h2 style="margin: 16px 0 0; color: #2563eb; font-size: 20px;">Pesanan Sedang Dikirim!</h2>
                            </div>
                            
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px;">
                                Halo <strong>{{ $order->user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Kabar baik! Pesanan Anda dengan nomor <strong>{{ $order->order_number }}</strong> sudah dalam perjalanan menuju alamat Anda.
                            </p>
                            
                            <!-- Tracking Info -->
                            <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 2px solid #f97316; border-radius: 12px; padding: 24px; margin-bottom: 24px; text-align: center;">
                                <p style="margin: 0 0 8px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Nomor Resi</p>
                                <p style="margin: 0; color: #ea580c; font-size: 24px; font-weight: 700; letter-spacing: 2px; font-family: 'Courier New', monospace;">{{ $order->tracking_number ?? '-' }}</p>
                                <p style="margin: 8px 0 0; color: #6b7280; font-size: 14px;">
                                    Kurir: <strong>{{ strtoupper($order->courier ?? '-') }}</strong> {{ $order->shipping_service ?? '' }}
                                </p>
                            </div>
                            
                            <!-- Delivery Address -->
                            <div style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px; color: #1f2937; font-size: 14px;">📍 Alamat Pengiriman</h3>
                                <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.6;">
                                    <strong>{{ $order->recipient_name }}</strong><br>
                                    {{ $order->delivery_address }}<br>
                                    {{ $order->destination_district }}, {{ $order->destination_city }}<br>
                                    {{ $order->destination_province }} {{ $order->destination_postal_code }}<br>
                                    📱 {{ $order->phone }}
                                </p>
                            </div>
                            
                            <!-- Order Summary -->
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 20px;">
                                <h3 style="margin: 0 0 12px; color: #1f2937; font-size: 14px;">📦 Ringkasan Pesanan</h3>
                                <table style="width: 100%; border-collapse: collapse;">
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">{{ $item->menu->name ?? 'Item' }} x{{ $item->quantity }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            
                            @if($order->biteship_tracking_url)
                            <!-- Track Button -->
                            <div style="text-align: center; margin-top: 24px;">
                                <a href="{{ $order->biteship_tracking_url }}" style="display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 14px;">
                                    🔍 Lacak Pesanan
                                </a>
                            </div>
                            @endif
                            
                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 13px; text-align: center; line-height: 1.6;">
                                Estimasi pengiriman tergantung dari jarak dan kondisi pengiriman.<br>
                                Jika ada pertanyaan, silakan hubungi kami.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f9fafb; border-radius: 0 0 16px 16px; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} N'Kitchen. Terima kasih telah berbelanja!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
