<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'last_message_at',
        'has_unread_admin',
        'has_unread_user',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'has_unread_admin' => 'boolean',
        'has_unread_user' => 'boolean',
    ];

    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';

    /**
     * Get the user that owns the conversation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all messages in this conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the last message in this conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Get or create a conversation for a user.
     */
    public static function getOrCreateForUser($userId)
    {
        $conversation = self::where('user_id', $userId)
            ->where('status', self::STATUS_OPEN)
            ->first();

        if (!$conversation) {
            $conversation = self::create([
                'user_id' => $userId,
                'status' => self::STATUS_OPEN,
            ]);
        }

        return $conversation;
    }

    /**
     * Update last message timestamp.
     */
    public function touchLastMessage()
    {
        $this->update(['last_message_at' => now()]);
    }

    /**
     * Scope for open conversations.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope for conversations with unread messages for admin.
     */
    public function scopeUnreadByAdmin($query)
    {
        return $query->where('has_unread_admin', true);
    }
}
