<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappTemplateValidator;

class WhatsappCloudApiService
{
    protected $apiKey;
    protected $phoneNumberId;
    protected $businessAccountId;
    protected $apiVersion;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.whatsapp.token') ?: env('WHATSAPP_API_KEY');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id') ?: env('WHATSAPP_PHONE_NUMBER_ID');
        $this->businessAccountId = config('services.whatsapp.business_account_id') ?: env('WHATSAPP_BUSINESS_ACCOUNT_ID');
        $this->apiVersion = 'v23.0'; // Updated to latest stable version
        $this->baseUrl = "https://graph.facebook.com/{$this->apiVersion}";
    }

    public function sendMessage($to, $message, $template = null, $templateParams = [], $mediaUrl = null, $mediaType = null)
    {
        if ($template) {
            return $this->sendTemplate($to, $template, $this->prepareTemplateComponents($templateParams));
        } elseif ($mediaUrl && $mediaType) {
            return $this->sendMedia($to, $mediaType, $mediaUrl, $message);
        } else {
            return $this->sendTextMessage($to, $message);
        }
    }

    public function fetchTemplates($approvedOnly = false, $forceRefresh = false)
    {
        // Validate configuration
        if (!$this->apiKey || !$this->businessAccountId) {
            throw new \Exception('WhatsApp API credentials not configured. Please check WHATSAPP_API_KEY and WHATSAPP_BUSINESS_ACCOUNT_ID in your .env file.');
        }
        
        $cacheKey = "whatsapp_templates_{$this->businessAccountId}" . ($approvedOnly ? '_approved' : '');
        
        if (!$forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $params = ['limit' => 1000];
            
            // Fetch only approved templates if requested
            if ($approvedOnly) {
                $params['status'] = 'APPROVED';
            }

            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->get("{$this->baseUrl}/{$this->businessAccountId}/message_templates", $params);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (!isset($responseData['data'])) {
                    throw new \Exception('Invalid response format from WhatsApp API');
                }
                
                $allTemplates = $responseData['data'];
                
                // Additional filter for approved status if needed
                $templates = $approvedOnly 
                    ? array_filter($allTemplates, function($template) {
                        return $template['status'] === 'APPROVED';
                    })
                    : $allTemplates;

                // Transform templates to include dynamic parameter detection
                $processedTemplates = array_map(function($template) {
                    return $this->processTemplateParameters($template);
                }, $templates);

                Cache::put($cacheKey, $processedTemplates, now()->addHours(6));
                
                // Sync with local database
                $this->syncTemplatesWithDatabase($processedTemplates);
                
                return $processedTemplates;
            } else {
                $errorData = $response->json();
                $errorMessage = isset($errorData['error']['message']) 
                    ? $errorData['error']['message'] 
                    : 'HTTP ' . $response->status() . ' error';
                    
                throw new \Exception("WhatsApp API error: {$errorMessage}");
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new \Exception('Network error connecting to WhatsApp API: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'WhatsApp API') === 0 || strpos($e->getMessage(), 'Network error') === 0) {
                throw $e; // Re-throw our custom exceptions
            }
            
            Log::error('WhatsApp API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Unexpected error fetching templates: ' . $e->getMessage());
        }
    }

    protected function processTemplateParameters($template)
    {
        $dynamicParams = [];
        
        // Extract parameters from template components
        if (isset($template['components'])) {
            foreach ($template['components'] as $component) {
                if ($component['type'] === 'BODY' && isset($component['text'])) {
                    // Find dynamic parameters using regex {{1}}, {{2}}, etc.
                    preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches);
                    
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $paramNumber) {
                            $dynamicParams[] = [
                                'position' => (int)$paramNumber,
                                'type' => 'text',
                                'component' => 'body'
                            ];
                        }
                    }
                }
                
                // Check for header parameters
                if ($component['type'] === 'HEADER' && isset($component['format'])) {
                    if ($component['format'] === 'TEXT' && isset($component['text'])) {
                        preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches);
                        if (!empty($matches[1])) {
                            foreach ($matches[1] as $paramNumber) {
                                $dynamicParams[] = [
                                    'position' => (int)$paramNumber,
                                    'type' => 'text',
                                    'component' => 'header'
                                ];
                            }
                        }
                    } elseif (in_array($component['format'], ['IMAGE', 'VIDEO', 'DOCUMENT'])) {
                        $dynamicParams[] = [
                            'position' => 1,
                            'type' => strtolower($component['format']),
                            'component' => 'header'
                        ];
                    }
                }

                // Check for button parameters (URL buttons with dynamic URLs)
                if ($component['type'] === 'BUTTONS') {
                    foreach ($component['buttons'] as $buttonIndex => $button) {
                        if ($button['type'] === 'URL' && isset($button['url']) && strpos($button['url'], '{{') !== false) {
                            preg_match_all('/\{\{(\d+)\}\}/', $button['url'], $matches);
                            if (!empty($matches[1])) {
                                foreach ($matches[1] as $paramNumber) {
                                    $dynamicParams[] = [
                                        'position' => (int)$paramNumber,
                                        'type' => 'text',
                                        'component' => 'button',
                                        'button_index' => $buttonIndex
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        // Sort parameters by position
        usort($dynamicParams, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        $template['dynamic_parameters'] = $dynamicParams;
        $template['has_dynamic_content'] = !empty($dynamicParams);
        
        return $template;
    }

    protected function syncTemplatesWithDatabase($templates)
    {
        try {
            foreach ($templates as $template) {
                $localTemplate = \App\Models\WhatsappTemplate::updateOrCreate(
                    ['whatsapp_id' => $template['id']],
                    [
                        'name' => $template['name'],
                        'status' => $template['status'],
                        'category' => $template['category'] ?? null,
                        'language' => $template['language'] ?? 'en',
                        'content' => $this->extractTemplateContent($template),
                        'parameters' => $template['dynamic_parameters'] ?? [],
                        'components' => $template['components'] ?? [],
                        'meta_data' => [
                            'quality_score' => $template['quality_score'] ?? null,
                            'rejection_reason' => $template['rejection_reason'] ?? null,
                            'last_synced' => now()->toISOString()
                        ]
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Template sync error', ['error' => $e->getMessage()]);
        }
    }

    protected function extractTemplateContent($template)
    {
        $content = '';
        
        if (isset($template['components'])) {
            foreach ($template['components'] as $component) {
                switch ($component['type']) {
                    case 'HEADER':
                        if (isset($component['text'])) {
                            $content .= "**" . $component['text'] . "**\n\n";
                        }
                        break;
                    case 'BODY':
                        if (isset($component['text'])) {
                            $content .= $component['text'] . "\n\n";
                        }
                        break;
                    case 'FOOTER':
                        if (isset($component['text'])) {
                            $content .= "_" . $component['text'] . "_\n\n";
                        }
                        break;
                    case 'BUTTONS':
                        $content .= "Buttons:\n";
                        foreach ($component['buttons'] as $button) {
                            $content .= "- " . $button['text'];
                            if ($button['type'] === 'URL' && isset($button['url'])) {
                                $content .= " (" . $button['url'] . ")";
                            }
                            $content .= "\n";
                        }
                        break;
                }
            }
        }
        
        return trim($content);
    }

    public function getTemplateById($templateId)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->get("{$this->baseUrl}/{$templateId}");

            if ($response->successful()) {
                return $this->processTemplateParameters($response->json());
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Get template by ID error', ['error' => $e->getMessage(), 'id' => $templateId]);
            return null;
        }
    }

    public function sendTemplate($to, $templateName, $components = [], $validateParams = true)
    {
        try {
            // Validate template and parameters if requested
            if ($validateParams) {
                $validation = WhatsappTemplateValidator::validateTemplate($templateName, $this->extractParametersFromComponents($components));
                
                if (!$validation['valid']) {
                    Log::error('Template Validation Failed', [
                        'template' => $templateName,
                        'error' => $validation['error'],
                        'to' => $to
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => $validation['error']
                    ];
                }
            }
            
            // Get the correct language code for this template
            $languageCode = $this->getTemplateLanguageCode($templateName);
            
            // Prepare template data
            $templateData = [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode
                ]
            ];
            
            // Only add components if they're not empty
            if (!empty($components)) {
                $templateData['components'] = $components;
            }
            
            $requestData = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => $templateData
            ];
            
            // Log the exact request for debugging
            Log::info('WhatsApp Template Request', [
                'template' => $templateName,
                'to' => $to,
                'language_code' => $languageCode,
                'request_data' => $requestData
            ]);
            
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", $requestData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id']
                ];
            }

            Log::error('WhatsApp Template Send Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'template' => $templateName,
                'to' => $to,
                'language_code' => $languageCode,
                'request_data' => $requestData
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Exception', [
                'message' => $e->getMessage(),
                'template' => $templateName,
                'to' => $to
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendTextMessage($to, $text)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $text
                    ]
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id']
                ];
            }

            Log::error('WhatsApp Text Message Send Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'to' => $to
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message']
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Exception', [
                'message' => $e->getMessage(),
                'to' => $to
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendMedia($to, $mediaType, $mediaUrl, $caption = null)
    {
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => $mediaType,
                $mediaType => [
                    'link' => $mediaUrl
                ]
            ];

            if ($caption && in_array($mediaType, ['image', 'video'])) {
                $payload[$mediaType]['caption'] = $caption;
            }

            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json()['messages'][0]['id']
                ];
            }

            Log::error('WhatsApp Media Send Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'type' => $mediaType,
                'to' => $to
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message']
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Media Send Exception', [
                'message' => $e->getMessage(),
                'type' => $mediaType,
                'to' => $to
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function prepareTemplateComponents($params)
    {
        if (empty($params)) {
            return [];
        }

        return [
            [
                'type' => 'body',
                'parameters' => array_map(function($param) {
                    if (is_array($param)) {
                        return [
                            'type' => $param['type'] ?? 'text',
                            'text' => $param['value'] ?? '',
                        ];
                    }
                    return [
                        'type' => 'text',
                        'text' => (string)$param
                    ];
                }, $params)
            ]
        ];
    }
    
    /**
     * Extract parameter values from components for validation
     */
    protected function extractParametersFromComponents($components)
    {
        $parameters = [];
        
        if (empty($components)) {
            return $parameters;
        }
        
        foreach ($components as $component) {
            if (isset($component['type']) && $component['type'] === 'body' && isset($component['parameters'])) {
                foreach ($component['parameters'] as $param) {
                    if (isset($param['text'])) {
                        $parameters[] = $param['text'];
                    }
                }
            }
        }
        
        return $parameters;
    }

    public function sendTemplateMessage($phone, $templateName, $parameters = [])
    {
        try {
            // Clean phone number
            $phone = preg_replace('/[^\d+]/', '', $phone);
            if (!str_starts_with($phone, '+')) {
                $phone = '+' . $phone;
            }

            // Prepare template message payload
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $this->getTemplateLanguageCode($templateName)
                    ]
                ]
            ];

            // Add parameters if provided
            if (!empty($parameters)) {
                $payload['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => array_map(function($param) {
                            return ['type' => 'text', 'text' => $param];
                        }, $parameters)
                    ]
                ];
            }

            // Send the message
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Template message sent successfully', [
                    'phone' => $phone,
                    'template' => $templateName,
                    'message_id' => $responseData['messages'][0]['id'] ?? null
                ]);
                return true;
            } else {
                $errorData = $response->json();
                Log::error('Failed to send template message', [
                    'phone' => $phone,
                    'template' => $templateName,
                    'error' => $errorData
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Exception sending template message', [
                'phone' => $phone,
                'template' => $templateName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get the correct language code for a specific template
     */
    private function getTemplateLanguageCode($templateName)
    {
        // Template-specific language mapping
        $templateLanguages = [
            'hello_world' => 'en_US',
            'doctor_flow_lead' => 'en'
        ];
        
        // Return specific language code if mapped, otherwise default to en_US
        return $templateLanguages[$templateName] ?? 'en_US';
    }
}
