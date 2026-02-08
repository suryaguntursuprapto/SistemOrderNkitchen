<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran</title>
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
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Invoice Pembayaran</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <!-- Success Badge -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="display: inline-block; background-color: #dcfce7; border-radius: 50%; padding: 16px;">
                                    <span style="font-size: 32px;">✅</span>
                                </div>
                                <h2 style="margin: 16px 0 0; color: #16a34a; font-size: 20px;">Pembayaran Berhasil!</h2>
                            </div>
                            
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px;">
                                Halo <strong>{{ $order->user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Terima kasih atas pembayaran Anda. Berikut adalah detail invoice pesanan Anda:
                            </p>
                            
                            <!-- Invoice Box -->
                            <div style="background-color: #f9fafb; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">No. Pesanan</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Tanggal Pembayaran</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; text-align: right;">{{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Metode Pembayaran</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; text-align: right;">{{ $payment->payment_type ?? 'Transfer' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Order Items -->
                            <h3 style="margin: 0 0 16px; color: #1f2937; font-size: 16px;">Detail Pesanan</h3>
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #fed7aa;">
                                        <th style="padding: 12px 0; text-align: left; color: #6b7280; font-size: 12px; text-transform: uppercase;">Item</th>
                                        <th style="padding: 12px 0; text-align: center; color: #6b7280; font-size: 12px; text-transform: uppercase;">Qty</th>
                                        <th style="padding: 12px 0; text-align: right; color: #6b7280; font-size: 12px; text-transform: uppercase;">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px 0; color: #1f2937; font-size: 14px;">{{ $item->menu->name ?? 'Item' }}</td>
                                        <td style="padding: 12px 0; text-align: center; color: #6b7280; font-size: 14px;">{{ $item->quantity }}</td>
                                        <td style="padding: 12px 0; text-align: right; color: #1f2937; font-size: 14px;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Totals -->
                            <div style="background-color: #fff7ed; border-radius: 12px; padding: 16px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Subtotal</td>
                                        <td style="padding: 6px 0; color: #1f2937; font-size: 14px; text-align: right;">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Ongkos Kirim</td>
                                        <td style="padding: 6px 0; color: #1f2937; font-size: 14px; text-align: right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr style="border-top: 2px solid #fed7aa;">
                                        <td style="padding: 12px 0 6px; color: #1f2937; font-size: 16px; font-weight: 700;">Total</td>
                                        <td style="padding: 12px 0 6px; color: #ea580c; font-size: 18px; font-weight: 700; text-align: right;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Status -->
                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 14px; text-align: center;">
                                📦 Pesanan Anda sedang diproses dan akan segera dikirim.
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
