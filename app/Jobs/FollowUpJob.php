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

class FollowUpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $leadStatus;

    public function __construct($phone, $leadStatus)
    {
        $this->phone = $phone;
        $this->leadStatus = $leadStatus;
    }

    public function handle()
    {
        $conversation = WhatsappConversation::where('phone', $this->phone)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$conversation) {
            return;
        }

        // Select appropriate follow-up template based on lead status
        $template = WhatsappTemplate::where('type', 'follow_up')
            ->where('lead_status', $this->leadStatus)
            ->where('is_active', true)
            ->first();

        if ($template) {
            $service = new WhatsappCloudApiService();
            $service->sendMessage($this->phone, '', $template->name, [
                $conversation->customer_name ?? 'valued customer'
            ]);

            // Update conversation record
            $conversation->update([
                'last_follow_up' => now(),
                'follow_up_template_id' => $template->id
            ]);
        }
    }
}
