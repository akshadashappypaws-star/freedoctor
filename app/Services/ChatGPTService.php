<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatGPTService
{
    private $apiKey;
    private $model;
    private $temperature;
    private $maxTokens;
    private $presencePenalty;
    private $frequencyPenalty;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model', 'gpt-3.5-turbo');
        $this->temperature = config('services.openai.temperature', 0.7);
        $this->maxTokens = config('services.openai.max_tokens', 300);
        $this->presencePenalty = config('services.openai.presence_penalty', 0.0);
        $this->frequencyPenalty = config('services.openai.frequency_penalty', 0.0);
    }

    public function generateResponse($message, $context = null, $systemMessage = null)
    {
        try {
            $messages = [];

            // Add system message if provided
            if ($systemMessage) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $systemMessage
                ];
            }

            // Add context if provided
            if ($context) {
                $messages[] = [
                    'role' => 'system',
                    'content' => "Context: {$context}"
                ];
            }

            // Add user message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
                'presence_penalty' => $this->presencePenalty,
                'frequency_penalty' => $this->frequencyPenalty,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['choices'][0]['message']['content'] ?? null;
            }

            Log::error('ChatGPT API Error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('ChatGPT Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    public function generateMedicalResponse($symptoms, $userContext = [])
    {
        $systemMessage = "You are a medical assistant providing preliminary information. Always:
1. Maintain a professional and caring tone
2. Ask for more details when needed
3. Encourage consulting a doctor for proper diagnosis
4. Provide general health information only
5. Highlight any potential emergency situations
6. Suggest relevant medical specialties";

        $context = "Patient Context:
- Previous Medical History: " . ($userContext['medical_history'] ?? 'Not provided') . "
- Age: " . ($userContext['age'] ?? 'Not provided') . "
- Gender: " . ($userContext['gender'] ?? 'Not provided') . "
- Duration of Symptoms: " . ($userContext['duration'] ?? 'Not provided');

        return $this->generateResponse($symptoms, $context, $systemMessage);
    }

    public function generateFollowUpQuestions($initialQuery)
    {
        $systemMessage = "You are a medical assistant. Generate 2-3 relevant follow-up questions to gather more information about the patient's condition. Questions should be:
1. Clear and specific
2. Relevant to the initial query
3. Help in understanding the severity
4. Aid in determining urgency
5. Appropriate for a medical context";

        return $this->generateResponse($initialQuery, null, $systemMessage);
    }

    public function analyzeSentiment($message)
    {
        $cacheKey = 'sentiment_' . md5($message);
        
        return Cache::remember($cacheKey, 3600, function () use ($message) {
            $systemMessage = "Analyze the sentiment of this message in the context of medical consultation. Classify as one of:
            - positive (showing interest, agreement, or satisfaction)
            - negative (showing disinterest, disagreement, or dissatisfaction)
            - neutral (factual or unclear sentiment)
            Respond with ONLY the classification word.";

            $response = $this->generateResponse($message, null, $systemMessage);
            return strtolower(trim($response));
        });
    }

    public function suggestResponseTemplate($message, $templates)
    {
        $systemMessage = "You are a template selection assistant. Based on the user's message, suggest the most appropriate response template from the available options. Consider:
1. Message intent
2. Urgency level
3. Stage in customer journey
4. Previous interaction context
Respond with ONLY the template name.";

        $context = "Available Templates:\n" . collect($templates)
            ->map(fn($t) => "- {$t['name']}: {$t['description']}")
            ->implode("\n");

        return $this->generateResponse($message, $context, $systemMessage);
    }
}
