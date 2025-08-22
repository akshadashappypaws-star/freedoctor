<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappConversation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'phone', 
        'message', 
        'message_id',
        'reply', 
        'reply_type', 
        'sent_at',
        'is_responded',
        'sentiment',
        'lead_status',
        'last_interaction'
    ];

    protected $dates = [
        'sent_at',
        'last_interaction'
    ];

    public function leadScore()
    {
        return $this->belongsTo(WhatsappLeadScore::class, 'phone', 'phone');
    }

    public function template()
    {
        return $this->belongsTo(WhatsappTemplate::class, 'template_id');
    }

    public function autoReply()
    {
        return $this->belongsTo(WhatsappAutoReply::class, 'auto_reply_id');
    }

    public function chatgptPrompt()
    {
        return $this->belongsTo(WhatsappChatGPTPrompt::class, 'chatgpt_prompt_id');
    }
}
