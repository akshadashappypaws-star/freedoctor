<?php

namespace App\Services;

use Illuminate\Support\Str;

class InteractionAnalyzer
{
    public function analyzeConversationFlow($conversations)
    {
        $flows = collect($conversations)->map(function ($conversation) {
            return [
                'phone' => $conversation->phone,
                'message_count' => $conversation->messages_count,
                'response_time_avg' => $conversation->avg_response_time,
                'interaction_points' => $this->calculateInteractionPoints($conversation),
                'engagement_level' => $this->calculateEngagementLevel($conversation),
                'topic_interests' => $this->extractTopicInterests($conversation),
                'conversion_stage' => $this->determineConversionStage($conversation)
            ];
        });

        return [
            'total_conversations' => $flows->count(),
            'avg_messages_per_conversation' => $flows->avg('message_count'),
            'avg_response_time' => $flows->avg('response_time_avg'),
            'engagement_distribution' => $flows->groupBy('engagement_level')->map->count(),
            'popular_topics' => $this->aggregateTopicInterests($flows),
            'conversion_funnel' => $flows->groupBy('conversion_stage')->map->count()
        ];
    }

    private function calculateInteractionPoints($conversation)
    {
        $points = 0;
        
        // Message frequency points
        $points += $conversation->messages_count * 2;
        
        // Quick response points
        if ($conversation->avg_response_time < 300) { // 5 minutes
            $points += 10;
        }
        
        // Positive sentiment points
        $positiveMessages = collect($conversation->messages)
            ->filter(fn($msg) => $msg->sentiment === 'positive')
            ->count();
        $points += $positiveMessages * 5;
        
        // Action points (bookings, appointments, etc.)
        $actionKeywords = ['book', 'appointment', 'schedule', 'confirm'];
        $actionMessages = collect($conversation->messages)
            ->filter(function($msg) use ($actionKeywords) {
                return Str::contains(strtolower($msg->message), $actionKeywords);
            })
            ->count();
        $points += $actionMessages * 10;
        
        return $points;
    }

    private function calculateEngagementLevel($conversation)
    {
        $points = $this->calculateInteractionPoints($conversation);
        
        if ($points >= 100) return 'high';
        if ($points >= 50) return 'medium';
        return 'low';
    }

    private function extractTopicInterests($conversation)
    {
        $topics = [
            'appointment' => ['book', 'schedule', 'appointment', 'visit'],
            'pricing' => ['cost', 'price', 'fee', 'payment'],
            'medical' => ['symptoms', 'pain', 'treatment', 'medicine'],
            'location' => ['where', 'location', 'address', 'clinic'],
            'availability' => ['available', 'when', 'timing', 'hours']
        ];

        $interests = [];
        foreach ($topics as $topic => $keywords) {
            $count = collect($conversation->messages)
                ->filter(function($msg) use ($keywords) {
                    return Str::contains(strtolower($msg->message), $keywords);
                })
                ->count();
            
            if ($count > 0) {
                $interests[$topic] = $count;
            }
        }

        return $interests;
    }

    private function determineConversionStage($conversation)
    {
        $messages = collect($conversation->messages);
        
        // Check if there's a confirmed booking/appointment
        if ($messages->contains(function($msg) {
            return Str::contains(strtolower($msg->message), ['confirmed', 'booked', 'scheduled'])
                && $msg->type === 'system';
        })) {
            return 'converted';
        }

        // Check if there's active interest
        if ($messages->contains(function($msg) {
            return Str::contains(strtolower($msg->message), ['interested', 'book', 'schedule', 'when'])
                && $msg->type === 'user';
        })) {
            return 'interested';
        }

        // Check if there's initial inquiry
        if ($messages->contains(function($msg) {
            return Str::contains(strtolower($msg->message), ['how', 'what', 'price', 'cost', 'where'])
                && $msg->type === 'user';
        })) {
            return 'inquiring';
        }

        return 'new';
    }

    private function aggregateTopicInterests($flows)
    {
        return $flows->flatMap(function($flow) {
                return $flow['topic_interests'];
            })
            ->groupBy(function($count, $topic) {
                return $topic;
            })
            ->map(function($counts) {
                return [
                    'total_mentions' => $counts->sum(),
                    'conversation_count' => $counts->count()
                ];
            })
            ->sortByDesc('total_mentions');
    }
}
