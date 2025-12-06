<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'conversation_id',
        'sender_type',
        'sender_id',
        'subject',
        'message',
        'message_status',
        'is_read',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    const SENDER_USER = 'user';
    const SENDER_ADMIN = 'admin';
    const SENDER_CHATBOT = 'chatbot';

    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_READ = 'read';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        if ($this->sender_type === self::SENDER_ADMIN) {
            return $this->belongsTo(User::class, 'sender_id');
        }
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('admin_reply');
    }

    public function scopeUnreplied($query)
    {
        return $query->whereNull('admin_reply');
    }

    public function scopeFromConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true, 'message_status' => self::STATUS_READ]);
    }

    /**
     * Check if message is from user
     */
    public function isFromUser()
    {
        return $this->sender_type === self::SENDER_USER;
    }

    /**
     * Check if message is from admin
     */
    public function isFromAdmin()
    {
        return $this->sender_type === self::SENDER_ADMIN;
    }

    /**
     * Check if message is from chatbot
     */
    public function isFromChatbot()
    {
        return $this->sender_type === self::SENDER_CHATBOT;
    }
}