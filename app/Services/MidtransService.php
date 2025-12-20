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
    // Fonnte WhatsApp API Configuration
    const FONNTE_TOKEN = 'f4HZoShcdaRSgW936fR4'; // <--- GANTI dengan token Fonnte Anda
    const ADMIN_PHONE_NUMBER = '6281334112002'; // <--- GANTI dengan nomor WA Admin (format 628...)

    /**
     * Mengirim notifikasi transaksi baru ke WhatsApp Admin via Fonnte.
     */
    public function sendWhatsAppNotification(Order $order, string $status)
    {
        if (empty(self::FONNTE_TOKEN)) {
            Log::warning('FONNTE ERROR: Token not configured.');
            return;
        }

        if (empty(self::ADMIN_PHONE_NUMBER)) {
            Log::warning('FONNTE ERROR: Admin Phone Number not configured.');
            return;
        }

        $orderAmount = number_format($order->total_amount, 0, ',', '.');
        $shippingCost = number_format($order->shipping_cost ?? 0, 0, ',', '.');
        $statusText = strtoupper($status);
        $courierName = strtoupper($order->courier ?? 'N/A');
        $shippingService = $order->shipping_service ?? 'Reguler';
        
        // Build full address with district, city, province, postal code
        $fullAddress = $order->delivery_address;
        $addressParts = [];
        if ($order->destination_district) {
            $addressParts[] = $order->destination_district;
        }
        if ($order->destination_city) {
            $addressParts[] = $order->destination_city;
        }
        if ($order->destination_province) {
            $addressParts[] = $order->destination_province;
        }
        if ($order->destination_postal_code) {
            $addressParts[] = $order->destination_postal_code;
        }
        $destinationDetails = implode(', ', $addressParts);
        
        // Build order items list
        $itemsList = "";
        $itemNumber = 1;
        foreach ($order->orderItems as $item) {
            $itemPrice = number_format($item->price * $item->quantity, 0, ',', '.');
            $itemsList .= "   {$itemNumber}. {$item->menu->name} x{$item->quantity} - Rp {$itemPrice}\n";
            $itemNumber++;
        }
        
        // Calculate subtotal (total - shipping)
        $subtotal = $order->total_amount - ($order->shipping_cost ?? 0);
        $subtotalFormatted = number_format($subtotal, 0, ',', '.');
        
        $message = "🔔 *NOTIFIKASI PESANAN BARU* 🔔\n";
        $message .= "Status: *{$statusText}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📦 *No. Order:* {$order->order_number}\n";
        $message .= "📅 *Tanggal:* " . $order->created_at->format('d M Y, H:i') . " WIB\n\n";
        $message .= "👤 *Penerima:* {$order->recipient_name}\n";
        $message .= "📞 *Telepon:* {$order->phone}\n\n";
        $message .= "📍 *Alamat Pengiriman:*\n{$fullAddress}\n";
        if ($destinationDetails) {
            $message .= "📌 *Tujuan:* {$destinationDetails}\n";
        }
        $message .= "\n🚚 *Kurir:* {$courierName} ({$shippingService})\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "🛒 *DAFTAR PESANAN:*\n";
        $message .= $itemsList;
        $message .= "\n━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💵 Subtotal Menu: Rp {$subtotalFormatted}\n";
        $message .= "🚛 Ongkos Kirim: Rp {$shippingCost}\n";
        $message .= "💰 *TOTAL BAYAR: Rp {$orderAmount}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "✅ Mohon segera cek dashboard admin untuk proses lebih lanjut.";

        try {
            // Fonnte API endpoint
            $response = Http::withHeaders([
                'Authorization' => self::FONNTE_TOKEN
            ])->post('https://api.fonnte.com/send', [
                'target' => self::ADMIN_PHONE_NUMBER,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ]);

            // Log full response untuk debugging
            Log::info('FONNTE RESPONSE', [
                'status_code' => $response->status(),
                'body' => $response->json(),
                'destination' => self::ADMIN_PHONE_NUMBER,
                'order_id' => $order->id
            ]);

            $responseData = $response->json();
            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                Log::info('FONNTE SUCCESS: Notifikasi WA terkirim.', ['order_id' => $order->id]);
            } else {
                Log::error('FONNTE FAILED: Gagal kirim WA.', [
                    'response' => $response->body(),
                    'status_code' => $response->status(),
                    'detail' => $responseData['detail'] ?? 'Unknown error'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('FONNTE EXCEPTION:', ['error' => $e->getMessage()]);
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

    /**
     * Refund a transaction via Midtrans API
     * 
     * @param string $orderId Midtrans order ID
     * @param float $amount Amount to refund
     * @param string $reason Reason for refund
     * @return array|null Refund response
     */
    public function refundTransaction($orderId, $amount, $reason = 'Order not processed within 24 hours')
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            
            $baseUrl = $isProduction 
                ? 'https://api.midtrans.com' 
                : 'https://api.sandbox.midtrans.com';
            
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post("{$baseUrl}/v2/{$orderId}/refund", [
                    'refund_key' => 'refund-' . $orderId . '-' . time(),
                    'amount' => (int) $amount,
                    'reason' => $reason,
                ]);
            
            if ($response->successful()) {
                Log::info("Midtrans refund successful for order {$orderId}", $response->json());
                return $response->json();
            } else {
                Log::error("Midtrans refund failed for order {$orderId}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Midtrans refund error for order {$orderId}: " . $e->getMessage());
            throw new \Exception('Midtrans Refund Error: ' . $e->getMessage());
        }
    }
}