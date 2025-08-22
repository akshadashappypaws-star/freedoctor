<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WhatsappConversation;
use App\Models\WhatsappTemplate;
use App\Services\WhatsappCloudApiService;

class DelayedResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $messageId;
    protected $templateId;
    protected $params;
    protected $conversationId;

    public function __construct($phone, $messageId, $templateId = null, $params = [], $conversationId = null)
    {
        $this->phone = $phone;
        $this->messageId = $messageId;
        $this->templateId = $templateId;
        $this->params = $params;
        $this->conversationId = $conversationId;
    }

    public function handle()
    {
        // Check if message was already responded to
        $conversation = WhatsappConversation::find($this->conversationId);
        if ($conversation && $conversation->is_responded) {
            return;
        }

        $service = new WhatsappCloudApiService();

        if ($this->templateId) {
            $template = WhatsappTemplate::find($this->templateId);
            if ($template) {
                $service->sendMessage($this->phone, '', $template->name, $this->params);
            }
        }

        // Mark message as responded
        if ($conversation) {
            $conversation->update([
                'is_responded' => true,
                'response_time' => now()->diffInSeconds($conversation->created_at),
                'response_type' => 'delayed_auto'
            ]);
        }
    }
}
