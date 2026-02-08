<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShippingNotification;

class BiteshipService
{
    private string $apiKey;
    private string $baseUrl;
    private array $shipperInfo;

    public function __construct()
    {
        $this->apiKey = config('services.biteship.api_key');
        $this->baseUrl = config('services.biteship.base_url', 'https://api.biteship.com');
        $this->shipperInfo = [
            'name' => config('services.biteship.shipper_name', 'N-Kitchen'),
            'phone' => config('services.biteship.shipper_phone', ''),
            'email' => config('services.biteship.shipper_email', ''),
            'address' => config('services.biteship.shipper_address', ''),
            'postal_code' => config('services.biteship.origin_postal_code', '41361'),
        ];
    }

    /**
     * Get headers for Biteship API requests
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create an order in Biteship and get AWB
     */
    public function createOrder(Order $order): ?array
    {
        try {
            // GoSend sekarang terintegrasi dengan Biteship (dengan koordinat)

            // Build items array dari order items
            $items = [];
            foreach ($order->orderItems as $item) {
                $items[] = [
                    'name' => $item->menu->name ?? 'N-Kitchen',
                    'description' => 'N-Kitchen',
                    'value' => (int) $item->price,
                    'quantity' => (int) $item->quantity,
                    'weight' => (int) (($item->menu->weight ?? 200) * $item->quantity),
                ];
            }

            // Map courier code untuk Biteship
            $courierCompany = $this->mapCourierCompany($order->courier);
            $courierType = $this->mapCourierType($order->shipping_service);
            
            // Check if instant courier (GoSend, Grab) - requires coordinates
            $instantCouriers = ['gosend', 'grab', 'gojek'];
            $isInstantCourier = in_array(strtolower($courierCompany), $instantCouriers);

            // Build request body sesuai dokumentasi Biteship
            $requestBody = [
                // Shipper info (pengirim) - optional
                'shipper_contact_name' => $this->shipperInfo['name'],
                'shipper_contact_phone' => $this->shipperInfo['phone'],
                'shipper_contact_email' => $this->shipperInfo['email'],
                'shipper_organization' => $this->shipperInfo['name'],
                
                // Origin (asal pengiriman) - REQUIRED
                'origin_contact_name' => $this->shipperInfo['name'],
                'origin_contact_phone' => $this->shipperInfo['phone'],
                'origin_address' => $this->shipperInfo['address'],
                'origin_postal_code' => (int) $this->shipperInfo['postal_code'], // Harus integer
                
                // Destination (tujuan pengiriman) - REQUIRED
                'destination_contact_name' => $order->recipient_name,
                'destination_contact_phone' => $order->phone,
                'destination_address' => $order->delivery_address,
                'destination_postal_code' => (int) $order->destination_postal_code, // Harus integer
                
                // Courier info - REQUIRED
                'courier_company' => $courierCompany,
                'courier_type' => $courierType,
                
                // Order type - REQUIRED (for instant couriers, 'now' means immediate pickup)
                'delivery_type' => 'now',
                
                // Items - REQUIRED
                'items' => $items,
                
                // Reference - optional
                'reference_id' => $order->order_number,
            ];
            
            // Add coordinates for instant couriers (GoSend, Grab) - REQUIRED for instant delivery
            if ($isInstantCourier) {
                // Origin coordinates as object (N-Kitchen location)
                $requestBody['origin_coordinate'] = [
                    'latitude' => -6.330311,
                    'longitude' => 107.298920
                ];
                
                // Destination coordinates as object from order
                if ($order->destination_latitude && $order->destination_longitude) {
                    $requestBody['destination_coordinate'] = [
                        'latitude' => (float) $order->destination_latitude,
                        'longitude' => (float) $order->destination_longitude
                    ];
                } else {
                    // Log warning if coordinates missing for instant courier
                    Log::warning('Instant courier order missing destination coordinates', [
                        'order_id' => $order->id,
                        'courier' => $courierCompany
                    ]);
                }
                
                // For instant couriers, use 'instant' courier type
                $requestBody['courier_type'] = 'instant';
                
                // GoSend uses 'gojek' as courier_company in Biteship
                if (strtolower($order->courier) === 'gosend') {
                    $requestBody['courier_company'] = 'gojek';
                }
            }

            Log::info('Creating Biteship order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'request_body' => $requestBody
            ]);

            $response = Http::withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/v1/orders', $requestBody);

            if (!$response->successful()) {
                $errorMessage = $response->json()['error'] ?? 'Gagal membuat order di Biteship';
                $errorCode = $response->json()['code'] ?? null;
                
                Log::error('Biteship Create Order Failed', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                // Handle specific error for instant courier unavailable
                if ($errorCode === 40002031 || str_contains($errorMessage, 'pickup service')) {
                    $courierName = strtoupper($order->courier ?? 'Kurir instan');
                    Log::warning("$courierName tidak tersedia di area ini", [
                        'order_id' => $order->id,
                        'origin' => $this->shipperInfo['postal_code'],
                        'destination' => $order->destination_postal_code
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => "$courierName tidak tersedia untuk area ini. Silakan pilih kurir lain atau hubungi admin.",
                        'is_service_unavailable' => true
                    ];
                }
                
                return [
                    'success' => false,
                    'error' => $errorMessage
                ];
            }

            $data = $response->json();
            
            // Update order dengan data Biteship
            $order->update([
                'biteship_order_id' => $data['id'] ?? null,
                'biteship_waybill_id' => $data['courier']['waybill_id'] ?? null,
                'tracking_number' => $data['courier']['waybill_id'] ?? null, // Juga update tracking_number
                'biteship_status' => $data['status'] ?? null,
                'biteship_label_url' => $data['courier']['link'] ?? null,
                'biteship_tracking_url' => $data['courier']['tracking_link'] ?? null,
            ]);

            Log::info('Biteship order created successfully', [
                'order_id' => $order->id,
                'biteship_order_id' => $data['id'] ?? null,
                'waybill_id' => $data['courier']['waybill_id'] ?? null
            ]);

            // 📧 Kirim email notifikasi pengiriman ke pembeli
            try {
                $order->load(['user', 'orderItems.menu']);
                if ($order->user && $order->user->email) {
                    Mail::to($order->user->email)->send(new ShippingNotification($order));
                    Log::info("Shipping notification email sent to: {$order->user->email}", [
                        'order_id' => $order->id,
                        'tracking_number' => $order->tracking_number
                    ]);
                }
            } catch (\Exception $emailException) {
                Log::error("Failed to send shipping notification email", [
                    'order_id' => $order->id,
                    'error' => $emailException->getMessage()
                ]);
            }

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('Biteship Create Order Exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get order status from Biteship
     */
    public function getOrder(string $biteshipOrderId): ?array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/v1/orders/' . $biteshipOrderId);

            if (!$response->successful()) {
                Log::error('Biteship Get Order Failed', [
                    'biteship_order_id' => $biteshipOrderId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Biteship Get Order Exception', [
                'biteship_order_id' => $biteshipOrderId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get tracking information for a shipment
     * GET /v1/trackings/:waybill_id/couriers/:courier_code
     * For instant couriers (gosend, grab), use order status endpoint instead
     */
    public function getTracking(string $waybillId, string $courierCode): ?array
    {
        try {
            // Map internal courier codes to Biteship courier codes
            $biteshipCourier = $this->mapCourierCompany($courierCode);
            
            Log::info('Getting Biteship tracking', [
                'waybill_id' => $waybillId,
                'courier_code' => $biteshipCourier
            ]);

            // Instant couriers (gosend, grab) don't have public tracking
            // Use order status endpoint instead if we have biteship_order_id
            $instantCouriers = ['gosend', 'grab', 'gojek'];
            if (in_array(strtolower($biteshipCourier), $instantCouriers)) {
                return $this->getInstantCourierTracking($waybillId, $biteshipCourier);
            }

            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/v1/trackings/' . $waybillId . '/couriers/' . $biteshipCourier);

            if (!$response->successful()) {
                Log::error('Biteship Get Tracking Failed', [
                    'waybill_id' => $waybillId,
                    'courier_code' => $biteshipCourier,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            
            Log::info('Biteship tracking retrieved', [
                'waybill_id' => $waybillId,
                'status' => $data['status'] ?? 'unknown'
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error('Biteship Get Tracking Exception', [
                'waybill_id' => $waybillId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get tracking for instant couriers (GoSend, Grab) via order status
     */
    private function getInstantCourierTracking(string $waybillOrOrderId, string $courierCode): ?array
    {
        try {
            // For instant couriers, we need to use the order ID to get status
            // The waybill might actually be the order ID
            $orderData = $this->getOrder($waybillOrOrderId);
            
            if (!$orderData || !isset($orderData['courier'])) {
                return [
                    'success' => true,
                    'status' => 'unknown',
                    'courier' => strtoupper($courierCode),
                    'history' => [
                        [
                            'note' => 'Tracking untuk kurir instant (GoSend/Grab) tidak tersedia secara publik. Pantau notifikasi dari aplikasi kurir.',
                            'updated_at' => now()->toIso8601String()
                        ]
                    ]
                ];
            }

            // Build tracking response from order data
            $status = $orderData['status'] ?? 'unknown';
            $courier = $orderData['courier'] ?? [];
            $history = [];

            // Create history entries from order status
            $statusMessages = [
                'confirmed' => 'Pesanan dikonfirmasi',
                'allocated' => 'Kurir telah dialokasikan',
                'picking_up' => 'Kurir sedang menjemput paket',
                'picked' => 'Paket telah dijemput kurir',
                'dropping_off' => 'Paket dalam pengiriman',
                'delivered' => 'Paket telah diterima',
                'cancelled' => 'Pengiriman dibatalkan',
                'rejected' => 'Pengiriman ditolak',
                'on_hold' => 'Pengiriman ditunda',
            ];

            if (isset($courier['history']) && is_array($courier['history'])) {
                foreach ($courier['history'] as $item) {
                    $history[] = [
                        'note' => $item['note'] ?? $item['status'] ?? 'Update',
                        'updated_at' => $item['updated_at'] ?? now()->toIso8601String()
                    ];
                }
            } else {
                // Just add current status if no history
                $history[] = [
                    'note' => $statusMessages[$status] ?? 'Status: ' . ucfirst($status),
                    'updated_at' => $orderData['updated_at'] ?? now()->toIso8601String()
                ];
            }

            return [
                'success' => true,
                'status' => $status,
                'courier' => strtoupper($courierCode),
                'waybill_id' => $courier['waybill_id'] ?? $waybillOrOrderId,
                'driver' => $courier['driver'] ?? null,
                'history' => array_reverse($history) // Most recent first
            ];

        } catch (\Exception $e) {
            Log::error('Instant courier tracking error', [
                'waybill_or_order_id' => $waybillOrOrderId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => true,
                'status' => 'unknown',
                'courier' => strtoupper($courierCode),
                'history' => [
                    [
                        'note' => 'Tracking untuk kurir instant tidak tersedia saat ini.',
                        'updated_at' => now()->toIso8601String()
                    ]
                ]
            ];
        }
    }

    /**
     * Cancel an order in Biteship
     * DELETE /v1/orders/:id
     */
    public function cancelOrder(string $biteshipOrderId, string $reason = 'Cancelled by admin'): ?array
    {
        try {
            Log::info('Cancelling Biteship order', [
                'biteship_order_id' => $biteshipOrderId,
                'reason' => $reason
            ]);

            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->baseUrl . '/v1/orders/' . $biteshipOrderId, [
                    'cancellation_reason' => $reason
                ]);

            if (!$response->successful()) {
                Log::error('Biteship Cancel Order Failed', [
                    'biteship_order_id' => $biteshipOrderId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'error' => $response->json()['error'] ?? 'Gagal membatalkan order di Biteship'
                ];
            }

            $data = $response->json();
            
            Log::info('Biteship order cancelled successfully', [
                'biteship_order_id' => $biteshipOrderId,
                'response' => $data
            ]);

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('Biteship Cancel Order Exception', [
                'biteship_order_id' => $biteshipOrderId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Map internal courier code to Biteship courier company
     */
    private function mapCourierCompany(string $courier): string
    {
        $mapping = [
            'jne' => 'jne',
            'sicepat' => 'sicepat',
            'jnt' => 'jnt',
            'gosend' => 'gosend',
            'grab' => 'grab',
            'anteraja' => 'anteraja',
            'pos' => 'pos',
            'tiki' => 'tiki',
            'ninja' => 'ninja',
            'lion' => 'lion',
            'wahana' => 'wahana',
        ];

        return $mapping[$courier] ?? $courier;
    }

    /**
     * Map shipping service to Biteship courier type
     */
    private function mapCourierType(?string $service): string
    {
        if (empty($service)) {
            return 'reg'; // Default to regular
        }

        // Convert service code ke lowercase dan bersihkan
        $service = strtolower($service);

        // Common mappings
        $mapping = [
            'reg' => 'reg',
            'oke' => 'oke', 
            'yes' => 'yes',
            'reguler' => 'reg',
            'regular' => 'reg',
            'express' => 'express',
            'eco' => 'eco',
            'halu' => 'halu', // SiCepat HALU
            'best' => 'best', // SiCepat BEST
            'ez' => 'ez', // J&T EZ
        ];

        // Cari di mapping
        foreach ($mapping as $key => $value) {
            if (str_contains($service, $key)) {
                return $value;
            }
        }

        // Default: return original atau 'reg'
        return $service ?: 'reg';
    }
}
