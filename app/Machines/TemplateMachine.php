<?php

namespace App\Machines;

use App\Models\WhatsappTemplate;
use App\Models\WhatsappTemplateCampaign;
use App\Services\WhatsAppService;
use Exception;

class TemplateMachine extends BaseMachine
{
    protected string $machineType = 'template';

    /**
     * Get default settings for Template machine
     */
    public static function getDefaultSettings(): array
    {
        return [
            'supported_types' => ['text', 'media', 'interactive', 'location'],
            'max_text_length' => 1600,
            'media_formats' => ['image', 'video', 'document', 'audio'],
            'template_engine' => 'blade',
            'variables_enabled' => true,
            'personalization' => true,
            'fallback_template' => 'generic_response',
            'delivery_tracking' => true,
            'retry_failed' => true,
            'max_retries' => 3,
            'rate_limit' => 100,
            'auto_save_drafts' => true
        ];
    }

    public function execute(array $input, int $stepNumber): array
    {
        return $this->safeExecute(function () use ($input) {
            $this->validateInput($input, ['action']);

            $action = $input['action'];
            $data = $input['data'] ?? [];
            $options = $input['options'] ?? [];

            return $this->processTemplate($action, $data, $options);
        }, $stepNumber, $input['action'] ?? 'unknown', $input);
    }

    /**
     * Process template based on action
     */
    private function processTemplate(string $action, array $data, array $options): array
    {
        switch ($action) {
            case 'sendDoctorList':
                return $this->sendDoctorList($data, $options);
            
            case 'sendHealthCampInfo':
                return $this->sendHealthCampInfo($data, $options);
            
            case 'sendRegistrationConfirmation':
                return $this->sendRegistrationConfirmation($data, $options);
            
            case 'sendPaymentReminder':
                return $this->sendPaymentReminder($data, $options);
            
            case 'sendWelcomeMessage':
                return $this->sendWelcomeMessage($data, $options);
            
            case 'sendErrorMessage':
                return $this->sendErrorMessage($data, $options);
            
            case 'sendGenericResponse':
                return $this->sendGenericResponse($data, $options);
            
            case 'selectTemplate':
                return $this->selectTemplate($data, $options);
            
            default:
                throw new Exception("Unknown template action: {$action}");
        }
    }

    /**
     * Send doctor list using template
     */
    private function sendDoctorList(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $doctors = $data['formatted_doctors'] ?? $data['doctors'] ?? [];

        if (empty($doctors)) {
            return $this->sendNoResultsMessage($whatsappNumber, 'doctors');
        }

        // Try to find appropriate template
        $template = $this->findTemplate('doctor_list');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'doctor_count' => count($doctors),
                'first_doctor_name' => $doctors[0]['name'] ?? 'Available Doctor',
                'search_area' => $options['search_area'] ?? 'your area'
            ]);
        } else {
            // Send as regular message
            $message = $this->formatDoctorListMessage($doctors, $options);
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send health camp information
     */
    private function sendHealthCampInfo(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $camps = $data['formatted_camps'] ?? $data['health_camps'] ?? [];

        if (empty($camps)) {
            return $this->sendNoResultsMessage($whatsappNumber, 'health camps');
        }

        $template = $this->findTemplate('health_camp_list');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'camp_count' => count($camps),
                'first_camp_title' => $camps[0]['title'] ?? 'Health Camp',
                'search_area' => $options['search_area'] ?? 'your area'
            ]);
        } else {
            $message = $this->formatHealthCampMessage($camps, $options);
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send registration confirmation
     */
    private function sendRegistrationConfirmation(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $registration = $data['formatted_registration'] ?? $data;

        $template = $this->findTemplate('registration_confirmation');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'patient_name' => $registration['patient_name'] ?? 'Patient',
                'campaign_title' => $registration['campaign_title'] ?? 'Health Camp',
                'registration_id' => $registration['registration_id'] ?? 'N/A',
                'amount' => $registration['amount_due'] ?? '0'
            ]);
        } else {
            $message = $this->formatRegistrationConfirmationMessage($registration);
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send payment reminder
     */
    private function sendPaymentReminder(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        
        $template = $this->findTemplate('payment_reminder');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'patient_name' => $data['patient_name'] ?? 'Patient',
                'amount' => $data['amount_due'] ?? '0',
                'due_date' => $data['due_date'] ?? 'soon'
            ]);
        } else {
            $message = "💰 *Payment Reminder*\n\nHi {$data['patient_name']}, please complete your payment of ₹{$data['amount_due']} to confirm your registration.\n\nPay now to secure your spot!";
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send welcome message
     */
    private function sendWelcomeMessage(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $userName = $data['user_name'] ?? 'there';

        $template = $this->findTemplate('welcome_message');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'user_name' => $userName
            ]);
        } else {
            $message = "🏥 *Welcome to FreeDoctor!*\n\nHi {$userName}! 👋\n\nI'm here to help you find:\n• 👨‍⚕️ Nearby doctors\n• 🏕️ Health camps\n• 📅 Book appointments\n• 💊 Medical information\n\nJust type what you're looking for and I'll assist you!";
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send error message
     */
    private function sendErrorMessage(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $errorType = $data['error_type'] ?? 'general';

        $template = $this->findTemplate('error_message');
        
        if ($template) {
            return $this->sendWhatsAppTemplate($template, $whatsappNumber, [
                'error_type' => $errorType
            ]);
        } else {
            $message = $this->getErrorMessage($errorType);
            return $this->sendWhatsAppMessage($whatsappNumber, $message);
        }
    }

    /**
     * Send generic response
     */
    private function sendGenericResponse(array $data, array $options): array
    {
        $whatsappNumber = $options['whatsapp_number'] ?? $data['whatsapp_number'];
        $message = $data['message'] ?? 'Thank you for contacting FreeDoctor. How can we help you today?';

        return $this->sendWhatsAppMessage($whatsappNumber, $message);
    }

    /**
     * Select appropriate template based on context
     */
    private function selectTemplate(array $data, array $options): array
    {
        $intent = $data['intent'] ?? 'general';
        $context = $data['context'] ?? [];

        $templateMap = [
            'find_doctor' => 'doctor_search_help',
            'find_health_camp' => 'health_camp_search_help',
            'book_appointment' => 'appointment_booking_help',
            'payment_inquiry' => 'payment_help',
            'general_inquiry' => 'general_help'
        ];

        $templateName = $templateMap[$intent] ?? 'general_help';
        $template = $this->findTemplate($templateName);

        if (!$template) {
            $template = $this->createFallbackTemplate($intent);
        }

        return [
            'selected_template' => $template->toArray(),
            'template_name' => $templateName,
            'intent' => $intent,
            'parameters_needed' => $this->extractTemplateParameters($template->body ?? '')
        ];
    }

    /**
     * Find template by name
     */
    private function findTemplate(string $templateName): ?WhatsappTemplate
    {
        return WhatsappTemplate::where('name', $templateName)
            ->where('status', 'APPROVED')
            ->first();
    }

    /**
     * Send WhatsApp template message
     */
    private function sendWhatsAppTemplate(WhatsappTemplate $template, string $whatsappNumber, array $parameters = []): array
    {
        try {
            $whatsappService = app(WhatsAppService::class);
            
            $response = $whatsappService->sendTemplate(
                $whatsappNumber,
                $template->name,
                $template->language_code ?? 'en',
                $parameters
            );

            // Log the sent template
            WhatsappTemplateCampaign::create([
                'template_id' => $template->id,
                'recipient_phone' => $whatsappNumber,
                'parameters' => json_encode($parameters),
                'status' => 'sent',
                'sent_at' => now()
            ]);

            return [
                'success' => true,
                'message_type' => 'template',
                'template_name' => $template->name,
                'recipient' => $whatsappNumber,
                'parameters' => $parameters,
                'whatsapp_response' => $response
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to send WhatsApp template: " . $e->getMessage());
        }
    }

    /**
     * Send regular WhatsApp message
     */
    private function sendWhatsAppMessage(string $whatsappNumber, string $message): array
    {
        try {
            $whatsappService = app(WhatsAppService::class);
            
            $response = $whatsappService->sendMessage($whatsappNumber, $message);

            return [
                'success' => true,
                'message_type' => 'text',
                'recipient' => $whatsappNumber,
                'message' => $message,
                'whatsapp_response' => $response
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to send WhatsApp message: " . $e->getMessage());
        }
    }

    /**
     * Send no results message
     */
    private function sendNoResultsMessage(string $whatsappNumber, string $searchType): array
    {
        $messages = [
            'doctors' => "😔 Sorry, no doctors found matching your criteria.\n\nTry:\n• Expanding your search radius\n• Different specialty\n• Different location\n\nType 'help' for more options.",
            'health_camps' => "😔 Sorry, no health camps found in your area.\n\nTry:\n• Expanding your search radius\n• Different dates\n• Different category\n\nType 'help' for more options."
        ];

        $message = $messages[$searchType] ?? "😔 Sorry, no results found. Please try a different search.";
        
        return $this->sendWhatsAppMessage($whatsappNumber, $message);
    }

    /**
     * Format doctor list message
     */
    private function formatDoctorListMessage(array $doctors, array $options): string
    {
        $message = "🏥 *Available Doctors*\n\n";
        
        foreach (array_slice($doctors, 0, 5) as $index => $doctor) {
            $message .= "*" . ($index + 1) . ". Dr. {$doctor['name']}*\n";
            $message .= "🏥 {$doctor['specialty']}\n";
            $message .= "👨‍⚕️ {$doctor['experience']}\n";
            $message .= "💰 {$doctor['fee']}\n";
            
            if (isset($doctor['distance'])) {
                $message .= "📍 {$doctor['distance']}\n";
            }
            
            $message .= "{$doctor['availability']}\n\n";
        }

        if (count($doctors) > 5) {
            $message .= "...and " . (count($doctors) - 5) . " more doctors.\n";
            $message .= "Type 'more doctors' to see all results.";
        }

        return $message;
    }

    /**
     * Format health camp message
     */
    private function formatHealthCampMessage(array $camps, array $options): string
    {
        $message = "🏕️ *Health Camps Available*\n\n";
        
        foreach (array_slice($camps, 0, 3) as $index => $camp) {
            $message .= "*" . ($index + 1) . ". {$camp['title']}*\n";
            $message .= "🏥 {$camp['category']}\n";
            $message .= "👨‍⚕️ Dr. {$camp['doctor']}\n";
            $message .= "📅 {$camp['date']} ({$camp['time']})\n";
            $message .= "📍 {$camp['location']}\n";
            
            if (isset($camp['distance'])) {
                $message .= "🚗 {$camp['distance']}\n";
            }
            
            $message .= "💰 {$camp['cost']}\n";
            $message .= "🎫 {$camp['slots']}\n";
            $message .= "{$camp['availability']}\n\n";
        }

        if (count($camps) > 3) {
            $message .= "...and " . (count($camps) - 3) . " more camps.\n";
            $message .= "Type 'more camps' to see all results.";
        }

        return $message;
    }

    /**
     * Format registration confirmation message
     */
    private function formatRegistrationConfirmationMessage(array $registration): string
    {
        $message = "✅ *Registration Confirmed!*\n\n";
        $message .= "📋 *Registration ID:* {$registration['registration_id']}\n";
        $message .= "🏥 *Event:* {$registration['campaign_title']}\n";
        $message .= "👤 *Patient:* {$registration['patient_name']}\n";
        $message .= "💰 *Amount:* {$registration['amount_due']}\n";
        $message .= "📅 *Registered:* {$registration['registration_date']}\n";
        $message .= "📊 *Status:* {$registration['status']}\n\n";

        if ($registration['payment_required']) {
            $message .= "⚠️ *Next Step:* Please complete your payment to confirm your registration.\n\n";
        }

        $message .= "Thank you for choosing FreeDoctor! 🙏";

        return $message;
    }

    /**
     * Get error message based on type
     */
    private function getErrorMessage(string $errorType): string
    {
        $errorMessages = [
            'location_not_found' => "📍 Sorry, I couldn't find that location. Please provide a more specific address or landmark.",
            'no_doctors_available' => "👨‍⚕️ No doctors are currently available for your request. Please try again later or contact support.",
            'payment_failed' => "💳 Payment processing failed. Please check your payment details and try again.",
            'registration_full' => "🎫 Sorry, this health camp is fully booked. Please check other available camps.",
            'invalid_request' => "❓ I didn't understand your request. Please try rephrasing or type 'help' for assistance.",
            'system_error' => "⚠️ We're experiencing technical difficulties. Please try again in a few minutes.",
            'general' => "😔 Something went wrong. Please try again or contact our support team."
        ];

        return $errorMessages[$errorType] ?? $errorMessages['general'];
    }

    /**
     * Create fallback template for unknown intents
     */
    private function createFallbackTemplate(string $intent): WhatsappTemplate
    {
        return new WhatsappTemplate([
            'name' => 'fallback_template',
            'body' => "I understand you're looking for help with {$intent}. Let me assist you with that. Please provide more details about what you need.",
            'language_code' => 'en',
            'status' => 'APPROVED'
        ]);
    }

    /**
     * Extract template parameters from template body
     */
    private function extractTemplateParameters(string $templateBody): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $templateBody, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Check if error is recoverable
     */
    protected function isRecoverableError(Exception $e): bool
    {
        $recoverableErrors = [
            'timeout',
            'rate_limit',
            'network',
            'temporary',
            'quota_exceeded'
        ];

        $message = strtolower($e->getMessage());
        
        foreach ($recoverableErrors as $error) {
            if (strpos($message, $error) !== false) {
                return true;
            }
        }

        return false;
    }
}
