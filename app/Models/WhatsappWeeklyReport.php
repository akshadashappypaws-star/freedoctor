<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappWeeklyReport extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_weekly_reports';

    protected $fillable = [
        'week_start',
        'week_end',
        'total_conversations',
        'new_conversations',
        'total_messages',
        'automated_messages',
        'user_categorization',
        'popular_keywords',
        'ai_insights',
        'automation_efficiency',
        'recommendations'
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'user_categorization' => 'array',
        'popular_keywords' => 'array',
        'ai_insights' => 'array',
        'recommendations' => 'array',
        'automation_efficiency' => 'decimal:2'
    ];

    public function scopeForWeek($query, $date)
    {
        $startOfWeek = $date->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        
        return $query->where('week_start', $startOfWeek->toDateString())
                    ->where('week_end', $endOfWeek->toDateString());
    }

    public static function generateWeeklyReport($weekStart)
    {
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        // Get conversation stats
        $conversations = WhatsappConversation::whereBetween('created_at', [$weekStart, $weekEnd])->get();
        $messages = WhatsappMessage::whereBetween('sent_at', [$weekStart, $weekEnd])->get();
        
        // Get user categorization
        $userBehavior = WhatsappUserBehavior::with('conversation')
            ->whereHas('conversation', function($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('last_message_at', [$weekStart, $weekEnd]);
            })->get();

        $categorization = [
            'interested' => $userBehavior->where('engagement_type', 'interested')->count(),
            'average' => $userBehavior->where('engagement_type', 'average')->count(),
            'not_interested' => $userBehavior->where('engagement_type', 'not_interested')->count()
        ];

        // Generate AI insights
        $aiAnalysis = WhatsappAiAnalysis::whereBetween('created_at', [$weekStart, $weekEnd])->get();
        $insights = [
            'total_ai_analysis' => $aiAnalysis->count(),
            'avg_confidence' => $aiAnalysis->avg('confidence_score'),
            'sentiment_distribution' => $aiAnalysis->groupBy('analysis_result')->map->count(),
            'top_intents' => $aiAnalysis->where('analysis_type', 'intent')
                ->groupBy('analysis_result')->map->count()->sortDesc()->take(5)
        ];

        return self::create([
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'total_conversations' => $conversations->count(),
            'new_conversations' => $conversations->where('created_at', '>=', $weekStart)->count(),
            'total_messages' => $messages->count(),
            'automated_messages' => $messages->where('is_automated', true)->count(),
            'user_categorization' => $categorization,
            'popular_keywords' => [], // Will be populated by keyword tracking
            'ai_insights' => $insights,
            'automation_efficiency' => $messages->where('is_automated', true)->count() / max($messages->count(), 1) * 100,
            'recommendations' => self::generateRecommendations($categorization, $insights)
        ]);
    }

    private static function generateRecommendations($categorization, $insights)
    {
        $recommendations = [];

        if ($categorization['not_interested'] > $categorization['interested']) {
            $recommendations[] = 'Consider reviewing automation rules to better engage users';
        }

        if ($insights['avg_confidence'] < 70) {
            $recommendations[] = 'AI confidence is low, consider training with more data';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'System is performing well, continue monitoring';
        }

        return $recommendations;
    }
}
