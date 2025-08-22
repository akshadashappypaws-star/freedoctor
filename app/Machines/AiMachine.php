<?php

namespace App\Machines;

use App\Models\Workflow;
use App\Models\WorkflowMachineConfig;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiMachine extends BaseMachine
{
    protected string $machineType = 'ai';

    /**
     * Get default settings for AI machine
     */
    public static function getDefaultSettings(): array
    {
        return [
            'api_provider' => 'openai',
            'model' => 'gpt-4',
            'max_tokens' => 1500,
            'temperature' => 0.7,
            'timeout' => 30,
            'retry_attempts' => 3,
            'context_window' => 4000,
            'response_format' => 'json',
            'capabilities' => [
                'intent_analysis',
                'plan_generation',
                'response_generation',
                'entity_extraction'
            ],
            'supported_languages' => ['en', 'hi', 'bn'],
            'fallback_response' => 'I apologize, but I need to better understand your request. Could you please rephrase it?'
        ];
    }

    public function execute(array $input, int $stepNumber): array
    {
        return $this->safeExecute(function () use ($input) {
            $this->validateInput($input, ['message', 'intent_type']);

            $message = $input['message'];
            $intentType = $input['intent_type']; // analyze_intent, generate_plan, generate_response

            switch ($intentType) {
                case 'analyze_intent':
                    return $this->analyzeIntent($message);
                
                case 'generate_plan':
                    return $this->generateWorkflowPlan($message, $input['context'] ?? []);
                
                case 'generate_response':
                    return $this->generateResponse($message, $input['context'] ?? []);
                
                default:
                    throw new Exception("Unsupported intent type: {$intentType}");
            }
        }, $stepNumber, $input['intent_type'], $input);
    }

    /**
     * Analyze user intent from message
     */
    private function analyzeIntent(string $message): array
    {
        $prompt = $this->buildIntentAnalysisPrompt($message);
        $response = $this->callOpenAI($prompt);

        // Parse the AI response to extract intent
        $intent = $this->parseIntentResponse($response);

        return [
            'intent' => $intent['intent'],
            'confidence' => $intent['confidence'],
            'entities' => $intent['entities'],
            'suggested_actions' => $intent['actions'],
            'raw_response' => $response
        ];
    }

    /**
     * Generate workflow execution plan
     */
    private function generateWorkflowPlan(string $message, array $context = []): array
    {
        $prompt = $this->buildPlanGenerationPrompt($message, $context);
        $response = $this->callOpenAI($prompt);

        // Parse the AI response to extract workflow plan
        $plan = $this->parsePlanResponse($response);

        return [
            'plan' => $plan['steps'],
            'estimated_time' => $plan['estimated_time'],
            'complexity' => $plan['complexity'],
            'required_data' => $plan['required_data'],
            'raw_response' => $response
        ];
    }

    /**
     * Generate response for user
     */
    private function generateResponse(string $message, array $context = []): array
    {
        $prompt = $this->buildResponseGenerationPrompt($message, $context);
        $response = $this->callOpenAI($prompt);

        return [
            'response' => $response,
            'tone' => $this->detectTone($response),
            'contains_action' => $this->containsActionItems($response),
            'confidence' => $this->calculateResponseConfidence($response)
        ];
    }

    /**
     * Build prompt for intent analysis
     */
    private function buildIntentAnalysisPrompt(string $message): string
    {
        $contexts = $this->getFreeDocterContexts();
        
        return "
        You are an AI assistant for FreeDoctor - a healthcare CRM system that helps patients find doctors, health camps, and medical services.

        Context about FreeDoctor:
        {$contexts}

        User Message: \"{$message}\"

        Analyze this message and respond in JSON format with:
        {
            \"intent\": \"primary_intent\",
            \"confidence\": 0.95,
            \"entities\": {
                \"location\": \"extracted_location\",
                \"specialty\": \"medical_specialty\",
                \"urgency\": \"high|medium|low\"
            },
            \"actions\": [\"suggested_action_1\", \"suggested_action_2\"]
        }

        Common intents: find_doctor, find_health_camp, book_appointment, ask_medical_question, get_location_info, payment_inquiry, complaint, general_inquiry
        ";
    }

    /**
     * Build prompt for plan generation
     */
    private function buildPlanGenerationPrompt(string $message, array $context): string
    {
        $contextStr = json_encode($context, JSON_PRETTY_PRINT);
        $freedoctorContexts = $this->getFreeDocterContexts();

        return "
        You are a workflow planner for FreeDoctor healthcare CRM system.

        FreeDoctor Context:
        {$freedoctorContexts}

        User Message: \"{$message}\"
        Additional Context: {$contextStr}

        Generate a step-by-step workflow plan in JSON format:
        {
            \"steps\": [
                {
                    \"step\": 1,
                    \"machine\": \"function\",
                    \"action\": \"searchNearbyDoctors\",
                    \"parameters\": {\"specialty\": \"cardiologist\", \"location\": \"delhi\", \"radius\": 10},
                    \"expected_output\": \"list_of_doctors\"
                },
                {
                    \"step\": 2,
                    \"machine\": \"datatable\",
                    \"action\": \"formatDoctorList\",
                    \"parameters\": {\"input\": \"list_of_doctors\", \"format\": \"whatsapp_template\"},
                    \"expected_output\": \"formatted_data\"
                }
            ],
            \"estimated_time\": \"30 seconds\",
            \"complexity\": \"medium\",
            \"required_data\": [\"user_location\", \"preferred_specialty\"]
        }

        Available machines: function, datatable, template, visualization
        Available functions: searchNearbyDoctors, findHealthCamps, checkAvailability, processPayment, sendNotification
        ";
    }

    /**
     * Build prompt for response generation
     */
    private function buildResponseGenerationPrompt(string $message, array $context): string
    {
        $contextStr = json_encode($context, JSON_PRETTY_PRINT);

        return "
        You are a helpful customer service assistant for FreeDoctor healthcare platform.

        User Message: \"{$message}\"
        Context: {$contextStr}

        Generate a helpful, professional, and empathetic response in WhatsApp-friendly format (max 1600 characters).

        Guidelines:
        - Be concise but informative
        - Use emojis appropriately
        - Include actionable steps when possible
        - Maintain professional yet friendly tone
        - If medical advice is needed, direct to qualified doctors
        - Include relevant contact information if needed
        ";
    }

    /**
     * Get FreeDoctor system contexts
     */
    private function getFreeDocterContexts(): string
    {
        return "
        FreeDoctor is a healthcare CRM platform with:
        - Doctor directory and appointments
        - Health camps and medical events
        - Patient registration and payments
        - WhatsApp-based customer support
        - Location-based medical services
        - Referral and earning systems for doctors
        - Admin panel for healthcare management
        
        Available services:
        - Find nearby doctors by specialty
        - Search health camps and events
        - Book appointments
        - Process payments via Razorpay
        - Track patient registrations
        - Doctor withdrawal and earning management
        ";
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI(string $prompt): string
    {
        $apiKey = config('services.openai.api_key');
        
        if (!$apiKey) {
            throw new Exception('OpenAI API key not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI assistant specialized in healthcare CRM systems and workflow automation.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1500,
                'temperature' => 0.7,
            ]);

            if (!$response->successful()) {
                throw new Exception("OpenAI API error: " . $response->body());
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? '';

        } catch (Exception $e) {
            Log::error('OpenAI API call failed', [
                'error' => $e->getMessage(),
                'prompt' => substr($prompt, 0, 500) . '...'
            ]);
            throw $e;
        }
    }

    /**
     * Parse intent analysis response
     */
    private function parseIntentResponse(string $response): array
    {
        try {
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response from AI');
            }
            return $decoded;
        } catch (Exception $e) {
            // Fallback parsing if JSON is malformed
            return [
                'intent' => 'general_inquiry',
                'confidence' => 0.5,
                'entities' => [],
                'actions' => ['manual_review']
            ];
        }
    }

    /**
     * Parse plan generation response
     */
    private function parsePlanResponse(string $response): array
    {
        try {
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response from AI');
            }
            return $decoded;
        } catch (Exception $e) {
            // Fallback plan
            return [
                'steps' => [
                    [
                        'step' => 1,
                        'machine' => 'template',
                        'action' => 'sendGenericResponse',
                        'parameters' => ['message' => 'I apologize, but I need to better understand your request.'],
                        'expected_output' => 'response_sent'
                    ]
                ],
                'estimated_time' => '10 seconds',
                'complexity' => 'low',
                'required_data' => []
            ];
        }
    }

    /**
     * Detect tone of response
     */
    private function detectTone(string $response): string
    {
        $response = strtolower($response);
        
        if (strpos($response, 'sorry') !== false || strpos($response, 'apologize') !== false) {
            return 'apologetic';
        }
        if (strpos($response, '!') !== false || strpos($response, 'great') !== false) {
            return 'enthusiastic';
        }
        if (strpos($response, '?') !== false) {
            return 'inquisitive';
        }
        
        return 'professional';
    }

    /**
     * Check if response contains action items
     */
    private function containsActionItems(string $response): bool
    {
        $actionWords = ['click', 'visit', 'call', 'contact', 'book', 'schedule', 'register', 'pay'];
        $response = strtolower($response);
        
        foreach ($actionWords as $word) {
            if (strpos($response, $word) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Calculate response confidence
     */
    private function calculateResponseConfidence(string $response): float
    {
        $length = strlen($response);
        
        if ($length < 50) return 0.6;
        if ($length < 200) return 0.8;
        if ($length < 500) return 0.9;
        
        return 0.95;
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
            'temporary'
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
