<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappAutoReply extends Model
{
    use HasFactory;
    protected $fillable = [
        'keyword', 'reply_type', 'reply_content', 'gpt_prompt'
    ];
}
