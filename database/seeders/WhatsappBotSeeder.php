<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappDefaultResponse;
use App\Models\WhatsappChatGPTContext;
use App\Models\WhatsappMessageFlow;

class WhatsappBotSeeder extends Seeder
{
    public function run()
    {
        try {
            // Default responses for common queries
            $this->createDefaultResponses();
        } catch (\Exception $e) {
            echo "Error creating default responses: " . $e->getMessage() . "\n";
        }

        try {
            // Message flows for different lead categories
            $this->createMessageFlows();
        } catch (\Exception $e) {
            echo "Error creating message flows: " . $e->getMessage() . "\n";
        }
    }

    private function createDefaultResponses()
    {
        $responses = [
            [
                'topic' => 'appointment_booking',
                'question_pattern' => '\b(book|schedule|appointment|consultation)\b',
                'answer' => 'Thank you for your interest in booking an appointment. Could you please let me know your preferred date and time?',
                'parameters' => ['date' => '\d{2}/\d{2}/\d{4}', 'time' => '\d{1,2}:\d{2}']
            ],
            [
                'topic' => 'pricing_inquiry',
                'question_pattern' => '\b(cost|price|fee|charge|payment)\b',
                'answer' => 'Our consultation fees start from ₹500. The exact cost depends on the type of consultation and duration. Would you like to know more about our packages?'
            ],
            [
                'topic' => 'location_query',
                'question_pattern' => '\b(where|location|address|clinic|hospital)\b',
                'answer' => 'We have multiple clinics across the city. Could you please share your area/locality so I can suggest the nearest clinic?'
            ],
            [
                'topic' => 'doctor_availability',
                'question_pattern' => '\b(doctor|available|timing|when)\b',
                'answer' => 'Our doctors are available from Monday to Saturday, 9 AM to 7 PM. Would you like me to check specific doctor\'s availability?'
            ],
            [
                'topic' => 'emergency',
                'question_pattern' => '\b(emergency|urgent|immediately|asap)\b',
                'answer' => 'For medical emergencies, please call our 24/7 emergency helpline: XXX-XXX-XXXX or visit the nearest emergency room.'
            ]
        ];

        foreach ($responses as $response) {
            WhatsappDefaultResponse::create($response);
        }
    }

    private function createChatGPTContexts()
    {
        $contexts = [
            [
                'topic' => 'medical_consultation',
                'system_message' => "You are a medical consultation assistant. Your role is to gather initial symptoms and provide general health information. Always recommend consulting a doctor for specific medical advice. Use a professional yet empathetic tone.",
                'context_data' => [
                    'keywords' => ['symptoms', 'pain', 'fever', 'cold', 'cough', 'headache'],
                    'required_info' => ['duration', 'severity', 'previous_conditions']
                ]
            ],
            [
                'topic' => 'appointment_booking',
                'system_message' => "You are an appointment scheduling assistant. Help patients book appointments by collecting relevant information like preferred time, doctor specialty, and location preference. Be efficient and clear in your communication.",
                'context_data' => [
                    'keywords' => ['book', 'appointment', 'schedule', 'meet', 'doctor'],
                    'required_info' => ['preferred_time', 'specialty', 'location']
                ]
            ],
            [
                'topic' => 'follow_up',
                'system_message' => "You are a follow-up care assistant. Check on patients' progress, remind them about medications and next appointments. Be caring and attentive to any concerns.",
                'context_data' => [
                    'keywords' => ['follow up', 'checkup', 'progress', 'medication', 'recovery'],
                    'required_info' => ['last_visit', 'current_status', 'concerns']
                ]
            ]
        ];

        foreach ($contexts as $context) {
            WhatsappChatGPTContext::create([
                'topic' => str_replace(' ', '_', strtolower($context['topic'])),
                'system_message' => $context['system_message'],
                'context_data' => $context['context_data'],
                'is_active' => true
            ]);
        }
    }

    private function createMessageFlows()
    {
        $flows = [
            [
                'name' => 'Valuable Customer Flow',
                'target_category' => 'valuable',
                'flow_steps' => [
                    [
                        'type' => 'message',
                        'template' => 'premium_offer',
                        'delay_hours' => 24
                    ],
                    [
                        'type' => 'message',
                        'template' => 'health_tips',
                        'delay_hours' => 72
                    ],
                    [
                        'type' => 'message',
                        'template' => 'personalized_followup',
                        'delay_hours' => 168
                    ]
                ]
            ],
            [
                'name' => 'Average Customer Flow',
                'target_category' => 'average',
                'flow_steps' => [
                    [
                        'type' => 'message',
                        'template' => 'general_offer',
                        'delay_hours' => 48
                    ],
                    [
                        'type' => 'message',
                        'template' => 'service_introduction',
                        'delay_hours' => 96
                    ]
                ]
            ],
            [
                'name' => 'Re-engagement Flow',
                'target_category' => 'not_interested',
                'flow_steps' => [
                    [
                        'type' => 'message',
                        'template' => 'special_offer',
                        'delay_hours' => 168
                    ],
                    [
                        'type' => 'message',
                        'template' => 'feedback_request',
                        'delay_hours' => 336
                    ]
                ]
            ]
        ];

        foreach ($flows as $flow) {
            WhatsappMessageFlow::create($flow);
        }
    }
}
