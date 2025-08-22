<?php

namespace App\Jobs;

use App\Models\WhatsappBulkMessage;
use App\Models\WhatsappConversation;
use App\Models\WhatsappLeadScore;
use App\Services\WhatsappCloudApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessScheduledWhatsappMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bulkMessage;

    public function __construct(WhatsappBulkMessage $bulkMessage)
    {
        $this->bulkMessage = $bulkMessage;
    }

    public function handle()
    {
        try {
            Log::info('Processing scheduled WhatsApp bulk message', ['id' => $this->bulkMessage->id]);

            // Update status to processing
            $this->bulkMessage->update(['status' => 'processing']);

            $whatsappService = new WhatsappCloudApiService();
            $recipients = json_decode($this->bulkMessage->recipients, true);
            $parameters = json_decode($this->bulkMessage->parameters, true) ?? [];
            $sent = 0;
            $failed = 0;
            $errors = [];

            foreach ($recipients as $phone) {
                try {
                    // Get the actual template parameters from the stored template
                    $templateParameters = $this->bulkMessage->template->parameters ?? [];
                    
                    // Only send parameters if the template actually expects them
                    $templateExpectsParameters = !empty($templateParameters);
                    
                    $dynamicParams = [];
                    if ($templateExpectsParameters && !empty($parameters)) {
                        $dynamicParams = $this->prepareDynamicParameters($phone, $parameters);
                    }
                    
                    $response = $whatsappService->sendMessage(
                        $phone,
                        null, // No direct message
                        $this->bulkMessage->template->name,
                        $dynamicParams
                    );

                    if ($response['success']) {
                        $sent++;
                        
                        // Record conversation
                        WhatsappConversation::create([
                            'phone' => $phone,
                            'message' => $this->bulkMessage->template->content,
                            'direction' => 'outbound',
                            'response_type' => 'template',
                            'template_id' => $this->bulkMessage->template_id,
                            'bulk_message_id' => $this->bulkMessage->id
                        ]);

                        // Update lead score
                        $leadScore = WhatsappLeadScore::firstOrCreate(['phone' => $phone]);
                        $leadScore->updateScore([
                            'type' => 'template_sent',
                            'score' => 1
                        ]);
                    } else {
                        $failed++;
                        $errors[] = "Failed to send to {$phone}: " . ($response['error'] ?? 'Unknown error');
                    }

                    // Add delay between messages to avoid rate limiting
                    usleep(500000); // 0.5 second delay
                    
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Error sending to {$phone}: " . $e->getMessage();
                    Log::error('Scheduled bulk message send error', [
                        'phone' => $phone,
                        'bulk_message_id' => $this->bulkMessage->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Update bulk message with results
            $this->bulkMessage->update([
                'status' => $failed === 0 ? 'completed' : 'failed',
                'sent_count' => $sent,
                'failed_count' => $failed,
                'error_details' => json_encode($errors),
                'sent_at' => now()
            ]);

            Log::info('Scheduled WhatsApp bulk message completed', [
                'id' => $this->bulkMessage->id,
                'sent' => $sent,
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process scheduled WhatsApp bulk message', [
                'id' => $this->bulkMessage->id,
                'error' => $e->getMessage()
            ]);

            $this->bulkMessage->update([
                'status' => 'failed',
                'error_details' => json_encode(['error' => $e->getMessage()])
            ]);

            throw $e;
        }
    }

    private function prepareDynamicParameters($phone, $baseParameters)
    {
        $leadScore = WhatsappLeadScore::where('phone', $phone)->first();
        $conversation = WhatsappConversation::where('phone', $phone)->orderBy('created_at', 'desc')->first();

        $dynamicParams = $baseParameters;

        // Only add dynamic parameters if we have base parameters to work with
        // This prevents sending unexpected parameters to templates that don't expect any
        if (!empty($baseParameters)) {
            // Add dynamic parameters based on user data
            if ($leadScore) {
                $dynamicParams['customer_name'] = $leadScore->customer_name ?? 'Valued Customer';
                $dynamicParams['interaction_count'] = $leadScore->interaction_count ?? 0;
                $dynamicParams['last_interaction'] = $leadScore->last_interaction ? 
                    $leadScore->last_interaction->diffForHumans() : 'recently';
            }

            if ($conversation) {
                $dynamicParams['last_message'] = substr($conversation->message, 0, 50);
            }
        }

        return $dynamicParams;
    }

    public function failed(\Exception $exception)
    {
        Log::error('Scheduled WhatsApp message job failed', [
            'bulk_message_id' => $this->bulkMessage->id,
            'error' => $exception->getMessage()
        ]);

        $this->bulkMessage->update([
            'status' => 'failed',
            'error_details' => json_encode(['job_error' => $exception->getMessage()])
        ]);
    }
}
