<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowConversationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'user_id',
        'whatsapp_number',
        'whatsapp_name',
        'message_type',
        'message_content',
        'message_id',
        'content_type',
        'media_url',
        'is_template_message',
        'template_name',
        'template_parameters',
        'delivery_status',
        'delivered_at',
        'read_at'
    ];

    protected $casts = [
        'template_parameters' => 'array',
        'is_template_message' => 'boolean',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Workflow this conversation belongs to
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * User who sent/received the message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'delivery_status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'delivery_status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Check if message is incoming
     */
    public function isIncoming(): bool
    {
        return $this->message_type === 'incoming';
    }

    /**
     * Check if message is outgoing
     */
    public function isOutgoing(): bool
    {
        return $this->message_type === 'outgoing';
    }

    /**
     * Check if message has media
     */
    public function hasMedia(): bool
    {
        return !empty($this->media_url);
    }

    /**
     * Static method to create incoming message
     */
    public static function createIncoming(
        string $whatsappNumber,
        string $messageContent,
        ?int $workflowId = null,
        ?int $userId = null,
        ?string $whatsappName = null,
        string $contentType = 'text',
        ?string $messageId = null,
        ?string $mediaUrl = null
    ): self {
        return self::create([
            'workflow_id' => $workflowId,
            'user_id' => $userId,
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_name' => $whatsappName,
            'message_type' => 'incoming',
            'message_content' => $messageContent,
            'message_id' => $messageId,
            'content_type' => $contentType,
            'media_url' => $mediaUrl,
            'delivery_status' => 'delivered'
        ]);
    }

    /**
     * Static method to create outgoing message
     */
    public static function createOutgoing(
        string $whatsappNumber,
        string $messageContent,
        ?int $workflowId = null,
        ?int $userId = null,
        bool $isTemplate = false,
        ?string $templateName = null,
        ?array $templateParameters = null,
        string $contentType = 'text',
        ?string $mediaUrl = null
    ): self {
        return self::create([
            'workflow_id' => $workflowId,
            'user_id' => $userId,
            'whatsapp_number' => $whatsappNumber,
            'message_type' => 'outgoing',
            'message_content' => $messageContent,
            'content_type' => $contentType,
            'media_url' => $mediaUrl,
            'is_template_message' => $isTemplate,
            'template_name' => $templateName,
            'template_parameters' => $templateParameters,
            'delivery_status' => 'sent'
        ]);
    }
}
