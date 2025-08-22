<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledWhatsappMessages;
use App\Models\WhatsappBulkMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled WhatsApp bulk messages that are due to be sent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled WhatsApp messages...');

        // Find scheduled messages that are due to be sent
        $scheduledMessages = WhatsappBulkMessage::where('is_scheduled', true)
            ->where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->with('template')
            ->get();

        if ($scheduledMessages->isEmpty()) {
            $this->info('No scheduled messages found to process.');
            return Command::SUCCESS;
        }

        $this->info("Found {$scheduledMessages->count()} scheduled message(s) to process.");

        foreach ($scheduledMessages as $message) {
            try {
                $this->info("Processing scheduled message ID: {$message->id}");
                
                // Dispatch the job to process this scheduled message
                ProcessScheduledWhatsappMessages::dispatch($message);
                
                $this->info("Dispatched job for message ID: {$message->id}");
                
                Log::info('Scheduled WhatsApp message dispatched', [
                    'message_id' => $message->id,
                    'scheduled_at' => $message->scheduled_at,
                    'template' => $message->template->name ?? 'N/A',
                    'recipients_count' => json_decode($message->recipients) ? count(json_decode($message->recipients)) : 0
                ]);
                
            } catch (\Exception $e) {
                $this->error("Failed to dispatch message ID {$message->id}: " . $e->getMessage());
                
                // Mark the message as failed
                $message->update([
                    'status' => 'failed',
                    'error_details' => json_encode(['dispatch_error' => $e->getMessage()])
                ]);
                
                Log::error('Failed to dispatch scheduled WhatsApp message', [
                    'message_id' => $message->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info('Finished processing scheduled WhatsApp messages.');
        return Command::SUCCESS;
    }
}
