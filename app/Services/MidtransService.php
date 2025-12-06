<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    const WAPISENDER_API_KEY = '2C5DC3EA-0A3F-41F0-AFFC-4B81F13BFFE1';
    const WAPISENDER_DEVICE_KEY = 'P6JUQJ'; // <--- GANTI INI
    const ADMIN_PHONE_NUMBER = '6281334112002'; // <--- GANTI INI (Nomor WA Admin, format 628...)

    /**
     * Mengirim notifikasi transaksi baru ke WhatsApp Admin.
     */
    public function sendWhatsAppNotification(Order $order, string $status)
    {
        if (empty(self::WAPISENDER_DEVICE_KEY) || empty(self::ADMIN_PHONE_NUMBER)) {
            Log::warning('WAPISENDER ERROR: Device Key or Admin Phone Number not configured.');
            return;
        }

        $base_url = "https://wapisender.id/api/v5/message/text";
        
        $orderAmount = number_format($order->total_amount, 0, ',', '.');
        $statusText = strtoupper($status);
        $courierName = strtoupper($order->courier ?? 'N/A');
        $shippingService = $order->shipping_service ?? 'Reguler';
        
        $message = "🔔 *NOTIFIKASI PESANAN BARU* 🔔\n";
        $message .= "Status: *{$statusText}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📦 No. Order: *{$order->order_number}*\n";
        $message .= "👤 Pelanggan: {$order->user->name}\n";
        $message .= "📞 Telepon: {$order->phone}\n";
        $message .= "💰 Total: *Rp {$orderAmount}*\n\n";
        $message .= "📍 Alamat Pengiriman:\n{$order->delivery_address}\n\n";
        $message .= "🚚 Kurir: *{$courierName}* ({$shippingService})\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Mohon segera cek dashboard admin untuk proses lebih lanjut.";

        try {
            $response = Http::post($base_url, [
                'api_key' => self::WAPISENDER_API_KEY,
                'device_key' => self::WAPISENDER_DEVICE_KEY,
                'destination' => self::ADMIN_PHONE_NUMBER,
                'message' => $message,
            ]);

            // Log full response untuk debugging
            Log::info('WAPISENDER RESPONSE', [
                'status_code' => $response->status(),
                'body' => $response->json(),
                'destination' => self::ADMIN_PHONE_NUMBER,
                'order_id' => $order->id
            ]);

            if ($response->successful() && $response->json('status') === 'ok') {
                Log::info('WAPISENDER SUCCESS: Notifikasi WA terkirim.', ['order_id' => $order->id]);
            } else {
                Log::error('WAPISENDER FAILED: Gagal kirim WA.', [
                    'response' => $response->body(),
                    'status_code' => $response->status()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('WAPISENDER EXCEPTION:', ['error' => $e->getMessage()]);
        }
    }
    
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createTransaction(Order $order)
    {
        // 1. Ambil biaya pengiriman dinamis dari model Order (sudah pasti ada dari DB)
        $shippingCost = intval($order->shipping_cost); 
        
        $item_details = []; // Gunakan item_details untuk konsistensi Midtrans
        $subtotalMenu = 0;

        // 2. Iterasi Item Pesanan
        foreach ($order->orderItems as $item) {
            $price = intval($item->price);
            $quantity = intval($item->quantity);

            $item_details[] = [
                'id' => $item->menu->id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => $item->menu->name,
                'category' => $item->menu->category ?? 'Food'
            ];
            $subtotalMenu += $price * $quantity;
        }

        // 3. Tambahkan Biaya Pengiriman sebagai item terpisah
        if ($shippingCost > 0) {
            $item_details[] = [
                'id' => 'SHIPPING_' . $order->id,
                'price' => $shippingCost, 
                'quantity' => 1,
                // Pastikan nama service dan kurir masuk
                'name' => 'Ongkos Kirim (' . ($order->courier ?? 'Kurir') . ' - ' . ($order->shipping_service ?? 'Reguler') . ')', 
                'merchant_name' => 'N-Kitchen'
            ];
        }

        // 4. Hitung Gross Amount (Grand Total)
        // Nilai ini harus sama dengan total harga dari semua item_details.
        $grandTotal = intval($order->total_amount); 

        // 5. Buat Order ID Midtrans baru
        $midtransOrderId = $order->order_number . '-' . time(); 
        
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId, 
                'gross_amount' => $grandTotal, // Grand Total (Menu + Ongkir)
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->phone,
                'billing_address' => [
                    'address' => $order->delivery_address,
                ],
                'shipping_address' => [
                    'address' => $order->delivery_address,
                ]
            ],
            'enabled_payments' => [
                'credit_card', 'mandiri_clickpay', 'cimb_clicks',
                'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel',
                'permata_va', 'bca_va', 'bni_va', 'other_va',
                'gopay', 'shopeepay', 'indomaret', 'alfamart'
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // 6. Update Payment Record yang ada
            if ($order->payment) {
                $order->payment->update([
                    'midtrans_order_id' => $midtransOrderId,
                    'amount' => $grandTotal
                ]);
            } else {
                // Fallback (tidak seharusnya dijalankan jika orderStore berhasil)
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method_id' => 1, 
                    'amount' => $grandTotal,
                    'midtrans_order_id' => $midtransOrderId,
                    'status' => 'pending'
                ]);
            }

            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus($orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }
}