<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiUrl;
    private ?string $accessToken;
    private ?string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');

        // Only throw exception when actually trying to use the service, not on construction
    }

    /**
     * Check if WhatsApp is properly configured
     */
    private function checkConfiguration(): void
    {
        if (!$this->accessToken || !$this->phoneNumberId) {
            throw new Exception('WhatsApp configuration missing. Please check access_token and phone_number_id in .env file');
        }
    }

    /**
     * Send a text message
     */
    public function sendMessage(string $to, string $message): array
    {
        $this->checkConfiguration();
        
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'text',
            'text' => ['body' => $message]
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Send a template message
     */
    public function sendTemplate(string $to, string $templateName, string $language = 'en', array $parameters = []): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $template = [
            'name' => $templateName,
            'language' => ['code' => $language]
        ];

        if (!empty($parameters)) {
            $template['components'] = $this->buildTemplateComponents($parameters);
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'template',
            'template' => $template
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Send media message (image, document, etc.)
     */
    public function sendMedia(string $to, string $mediaType, string $mediaUrl, string $caption = null): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $mediaPayload = ['link' => $mediaUrl];
        if ($caption) {
            $mediaPayload['caption'] = $caption;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => $mediaType,
            $mediaType => $mediaPayload
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Send location message
     */
    public function sendLocation(string $to, float $latitude, float $longitude, string $name = null, string $address = null): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $location = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        if ($name) $location['name'] = $name;
        if ($address) $location['address'] = $address;

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'location',
            'location' => $location
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Send interactive button message
     */
    public function sendButtons(string $to, string $bodyText, array $buttons, string $header = null, string $footer = null): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $interactive = [
            'type' => 'button',
            'body' => ['text' => $bodyText],
            'action' => [
                'buttons' => array_map(function ($button, $index) {
                    return [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $button['id'] ?? "btn_$index",
                            'title' => $button['title']
                        ]
                    ];
                }, $buttons, array_keys($buttons))
            ]
        ];

        if ($header) {
            $interactive['header'] = ['type' => 'text', 'text' => $header];
        }

        if ($footer) {
            $interactive['footer'] = ['text' => $footer];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'interactive',
            'interactive' => $interactive
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Send interactive list message
     */
    public function sendList(string $to, string $bodyText, array $sections, string $buttonText = 'Select Option', string $header = null, string $footer = null): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $interactive = [
            'type' => 'list',
            'body' => ['text' => $bodyText],
            'action' => [
                'button' => $buttonText,
                'sections' => $sections
            ]
        ];

        if ($header) {
            $interactive['header'] = ['type' => 'text', 'text' => $header];
        }

        if ($footer) {
            $interactive['footer'] = ['text' => $footer];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'interactive',
            'interactive' => $interactive
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(string $messageId): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId
        ];

        return $this->makeRequest($url, $payload);
    }

    /**
     * Get media URL from media ID
     */
    public function getMediaUrl(string $mediaId): string
    {
        $url = "{$this->apiUrl}/{$mediaId}";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ])->get($url);

        if (!$response->successful()) {
            throw new Exception("Failed to get media URL: " . $response->body());
        }

        $data = $response->json();
        return $data['url'] ?? '';
    }

    /**
     * Download media from WhatsApp
     */
    public function downloadMedia(string $mediaUrl): string
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ])->get($mediaUrl);

        if (!$response->successful()) {
            throw new Exception("Failed to download media: " . $response->body());
        }

        return $response->body();
    }

    /**
     * Upload media to WhatsApp
     */
    public function uploadMedia(string $filePath, string $type = 'image'): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/media";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ])->attach('file', file_get_contents($filePath), basename($filePath))
          ->post($url, [
              'messaging_product' => 'whatsapp',
              'type' => $type
          ]);

        if (!$response->successful()) {
            throw new Exception("Failed to upload media: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Get business profile
     */
    public function getBusinessProfile(): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/whatsapp_business_profile";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ])->get($url, [
            'fields' => 'about,address,description,email,profile_picture_url,websites,vertical'
        ]);

        if (!$response->successful()) {
            throw new Exception("Failed to get business profile: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Update business profile
     */
    public function updateBusinessProfile(array $profileData): array
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/whatsapp_business_profile";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ])->post($url, $profileData);

        if (!$response->successful()) {
            throw new Exception("Failed to update business profile: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Make HTTP request to WhatsApp API
     */
    private function makeRequest(string $url, array $payload): array
    {
        try {
            Log::info('WhatsApp API Request', [
                'url' => $url,
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json'
            ])->timeout(30)->post($url, $payload);

            $responseData = $response->json();

            Log::info('WhatsApp API Response', [
                'status' => $response->status(),
                'response' => $responseData
            ]);

            if (!$response->successful()) {
                throw new Exception("WhatsApp API error: " . ($responseData['error']['message'] ?? $response->body()));
            }

            return $responseData;

        } catch (Exception $e) {
            Log::error('WhatsApp API Request Failed', [
                'url' => $url,
                'payload' => $payload,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Format phone number for WhatsApp API
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters except +
        $phoneNumber = preg_replace('/[^+\d]/', '', $phoneNumber);

        // If number starts with +, remove it (API expects without +)
        if (str_starts_with($phoneNumber, '+')) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        // If it's an Indian number starting with 91, ensure it's properly formatted
        if (str_starts_with($phoneNumber, '91') && strlen($phoneNumber) === 12) {
            return $phoneNumber;
        }

        // If it's a 10-digit Indian number, add country code
        if (strlen($phoneNumber) === 10 && is_numeric($phoneNumber)) {
            return '91' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Build template components from parameters
     */
    private function buildTemplateComponents(array $parameters): array
    {
        $components = [];

        if (!empty($parameters)) {
            $bodyParameters = [];
            foreach ($parameters as $key => $value) {
                $bodyParameters[] = [
                    'type' => 'text',
                    'text' => (string) $value
                ];
            }

            $components[] = [
                'type' => 'body',
                'parameters' => $bodyParameters
            ];
        }

        return $components;
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $appSecret = config('services.whatsapp.app_secret');
        
        if (!$appSecret) {
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Verify webhook
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): string
    {
        $verifyToken = config('services.whatsapp.verify_token');
        
        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }
        
        throw new Exception('Webhook verification failed');
    }
}
