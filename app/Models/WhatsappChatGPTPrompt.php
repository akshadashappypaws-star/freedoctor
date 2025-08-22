<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappChatGPTPrompt extends Model
{
    protected $table = 'whatsapp_chat_g_p_t_prompts';
    
    protected $fillable = [
        'name',
        'prompt',
        'description',
        'category',
        'is_active',
        'usage_count',
        'variables',
        'example_response',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
        'usage_count' => 'integer',
        'priority' => 'integer'
    ];

    public function conversations()
    {
        return $this->hasMany(WhatsappConversation::class, 'chatgpt_prompt_id');
    }
    
    /**
     * Scope for active prompts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope for specific category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    
    /**
     * Scope ordered by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('usage_count', 'desc');
    }
    
    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}
