<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'type',
        'status',
        'message_id',
        'media_url',
        'media_type',
        'metadata',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the conversation that owns the message
     */
    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class, 'conversation_id');
    }

    /**
     * Get the user that sent the message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for inbound messages
     */
    public function scopeInbound($query)
    {
        return $query->where('type', 'inbound');
    }

    /**
     * Scope for outbound messages
     */
    public function scopeOutbound($query)
    {
        return $query->where('type', 'outbound');
    }

    /**
     * Scope for sent messages
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for delivered messages
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if message has media
     */
    public function hasMedia()
    {
        return !empty($this->media_url);
    }

    /**
     * Get formatted message time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Get formatted message date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    /**
     * Get human readable time difference
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if message is from today
     */
    public function isToday()
    {
        return $this->created_at->isToday();
    }

    /**
     * Check if message is recent (within last hour)
     */
    public function isRecent()
    {
        return $this->created_at->diffInHours(now()) < 1;
    }

    /**
     * Get truncated message content
     */
    public function getTruncatedMessageAttribute($limit = 100)
    {
        return strlen($this->message) > $limit 
            ? substr($this->message, 0, $limit) . '...' 
            : $this->message;
    }

    /**
     * Scope for recent messages
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours($hours));
    }

    /**
     * Scope for messages by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get message status with icon
     */
    public function getStatusWithIconAttribute()
    {
        $icons = [
            'sent' => '✓',
            'delivered' => '✓✓',
            'read' => '✓✓',
            'failed' => '✗',
            'pending' => '⏱'
        ];

        return $icons[$this->status] ?? '';
    }

    /**
     * Check if message needs retry
     */
    public function needsRetry()
    {
        return $this->status === 'failed' && 
               $this->type === 'outbound' && 
               $this->created_at->diffInHours(now()) < 24;
    }

    /**
     * Create a new message
     */
    public static function createMessage($data)
    {
        return self::create([
            'conversation_id' => $data['conversation_id'],
            'user_id' => $data['user_id'] ?? null,
            'message' => $data['message'],
            'type' => $data['type'] ?? 'inbound',
            'status' => $data['status'] ?? 'sent',
            'message_id' => $data['message_id'] ?? null,
            'media_url' => $data['media_url'] ?? null,
            'media_type' => $data['media_type'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);
    }
}
