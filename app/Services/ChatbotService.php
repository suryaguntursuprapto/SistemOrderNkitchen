<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Process incoming message and generate chatbot response if applicable
     */
    public function processMessage(string $userMessage, Conversation $conversation): ?Message
    {
        $userMessage = strtolower(trim($userMessage));
        
        // Check for order status query first (more specific)
        if (preg_match('/(status|pesanan|order|resi|lacak|tracking)/i', $userMessage)) {
            return $this->handleOrderStatusQuery($conversation);
        }
        
        // Check for menu query
        if (preg_match('/(harga|menu|berapa|daftar|price)/i', $userMessage)) {
            return $this->handleMenuQuery($conversation);
        }
        
        // Jam Operasional
        if (preg_match('/(jam|buka|tutup|operasional|waktu)/i', $userMessage)) {
            $message = "🕐 *Jam Operasional N-Kitchen*\n\n";
            $message .= "📅 Senin - Sabtu: 08:00 - 20:00 WIB\n";
            $message .= "📅 Minggu: 10:00 - 18:00 WIB\n\n";
            $message .= "⚠️ Hari libur nasional mungkin berbeda.";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // Pengiriman & Ongkir
        if (preg_match('/(ongkir|kirim|delivery|antar|pengiriman|ekspedisi)/i', $userMessage)) {
            $message = "🚚 *Info Pengiriman*\n\n";
            $message .= "Kami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n";
            $message .= "• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n";
            $message .= "💡 Ongkir dihitung otomatis saat checkout.";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // Pembayaran
        if (preg_match('/(bayar|pembayaran|transfer|payment|bank|qris|gopay|ovo)/i', $userMessage)) {
            $message = "💳 *Metode Pembayaran*\n\n";
            $message .= "• Transfer Bank (BCA, BNI, Mandiri, BRI)\n";
            $message .= "• Virtual Account\n";
            $message .= "• QRIS\n";
            $message .= "• GoPay & ShopeePay\n\n";
            $message .= "🔒 Semua transaksi aman melalui Midtrans.";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // Cara Pesan
        if (preg_match('/(cara|beli|checkout)/i', $userMessage)) {
            $message = "📦 *Cara Memesan*\n\n";
            $message .= "1️⃣ Pilih menu yang diinginkan\n";
            $message .= "2️⃣ Tambahkan ke keranjang\n";
            $message .= "3️⃣ Isi data pengiriman\n";
            $message .= "4️⃣ Pilih metode pembayaran\n";
            $message .= "5️⃣ Selesaikan pembayaran\n\n";
            $message .= "✅ Pesanan akan diproses setelah pembayaran dikonfirmasi!";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // Greeting
        if (preg_match('/(halo|hai|hi|hello|selamat)/i', $userMessage)) {
            $message = "👋 Halo! Selamat datang di N-Kitchen Pempek!\n\n";
            $message .= "Saya adalah asisten virtual yang siap membantu. Silakan tanyakan tentang:\n";
            $message .= "• Menu & Harga\n";
            $message .= "• Status Pesanan\n";
            $message .= "• Jam Operasional\n";
            $message .= "• Info Pengiriman\n";
            $message .= "• Metode Pembayaran\n\n";
            $message .= "Atau ketik pertanyaan Anda! 😊";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // Terima kasih
        if (preg_match('/(terima kasih|makasih|thanks|thank you)/i', $userMessage)) {
            $message = "🙏 Sama-sama! Senang bisa membantu Anda.\n\n";
            $message .= "Jika ada pertanyaan lain, jangan ragu untuk bertanya. Selamat berbelanja di N-Kitchen! 🍽️";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        // No pattern matched - send default response
        return $this->createDefaultResponse($conversation);
    }

    /**
     * Handle menu query with actual menu data
     */
    protected function handleMenuQuery(Conversation $conversation): Message
    {
        $menus = Menu::where('is_available', true)->orderBy('category')->get();
        
        if ($menus->isEmpty()) {
            $message = "🍽️ *Menu N-Kitchen*\n\n";
            $message .= "Maaf, saat ini belum ada menu yang tersedia.\n";
            $message .= "Silakan hubungi admin untuk informasi lebih lanjut.";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        $message = "🍽️ *Menu & Harga N-Kitchen*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $currentCategory = '';
        foreach ($menus as $menu) {
            if ($currentCategory !== $menu->category) {
                $currentCategory = $menu->category;
                $message .= "📌 *{$currentCategory}*\n";
            }
            $price = number_format($menu->price, 0, ',', '.');
            $message .= "• {$menu->name} - Rp {$price}\n";
        }
        
        $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 Kunjungi halaman Menu untuk pemesanan!";
        
        return $this->createChatbotMessage($conversation, $message);
    }

    /**
     * Handle order status query
     */
    protected function handleOrderStatusQuery(Conversation $conversation): Message
    {
        $userId = $conversation->user_id;
        
        // Get latest orders for this user
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        if ($orders->isEmpty()) {
            $message = "📦 *Status Pesanan*\n\n";
            $message .= "Anda belum memiliki pesanan.\n\n";
            $message .= "Yuk, mulai berbelanja di menu kami! 🍽️";
            return $this->createChatbotMessage($conversation, $message);
        }
        
        $message = "📦 *Status Pesanan Anda*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        foreach ($orders as $order) {
            $statusEmoji = $this->getStatusEmoji($order->status);
            $statusLabel = Order::getStatuses()[$order->status] ?? $order->status;
            $date = $order->created_at->format('d M Y');
            $total = number_format($order->total_amount, 0, ',', '.');
            
            $message .= "🔖 *{$order->order_number}*\n";
            $message .= "   📅 {$date}\n";
            $message .= "   💰 Rp {$total}\n";
            $message .= "   {$statusEmoji} {$statusLabel}\n";
            
            // Show tracking number if available
            if ($order->tracking_number) {
                $courierName = strtoupper($order->courier ?? 'Kurir');
                $message .= "   🚚 No. Resi: {$order->tracking_number} ({$courierName})\n";
            }
            
            $message .= "\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 Lihat detail di menu Riwayat Pesanan";
        
        return $this->createChatbotMessage($conversation, $message);
    }

    /**
     * Get emoji for order status
     */
    protected function getStatusEmoji(string $status): string
    {
        return match($status) {
            'pending' => '⏳',
            'confirmed' => '✅',
            'preparing' => '👨‍🍳',
            'ready' => '📦',
            'delivered' => '🎉',
            'cancelled' => '❌',
            default => '📋'
        };
    }

    /**
     * Create a chatbot message
     */
    protected function createChatbotMessage(Conversation $conversation, string $messageText): Message
    {
        $message = Message::create([
            'user_id' => $conversation->user_id,
            'conversation_id' => $conversation->id,
            'sender_type' => Message::SENDER_CHATBOT,
            'sender_id' => null,
            'subject' => 'Chatbot Response',
            'message' => $messageText,
            'message_status' => Message::STATUS_SENT,
            'is_read' => false,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'has_unread_user' => true,
        ]);

        Log::info('Chatbot response sent', [
            'conversation_id' => $conversation->id,
            'message_preview' => substr($messageText, 0, 50)
        ]);

        return $message;
    }

    /**
     * Create default response when no pattern matches
     */
    protected function createDefaultResponse(Conversation $conversation): Message
    {
        $defaultMessage = "🤖 Terima kasih atas pesan Anda!\n\n";
        $defaultMessage .= "Saya belum bisa menjawab pertanyaan ini secara otomatis. ";
        $defaultMessage .= "Admin kami akan segera membalas pesan Anda.\n\n";
        $defaultMessage .= "⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\n";
        $defaultMessage .= "Sambil menunggu, Anda bisa tanyakan:\n";
        $defaultMessage .= "• Menu & harga\n";
        $defaultMessage .= "• Status pesanan\n";
        $defaultMessage .= "• Jam operasional\n";
        $defaultMessage .= "• Info pengiriman";

        return $this->createChatbotMessage($conversation, $defaultMessage);
    }

    /**
     * Get quick replies/suggestions
     */
    public function getQuickReplies(): array
    {
        return [
            'Menu & Harga',
            'Status Pesanan',
            'Jam Buka',
            'Info Ongkir',
            'Pembayaran',
        ];
    }
}
