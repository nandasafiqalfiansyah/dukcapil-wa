<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'is_connected_to_whatsapp',
        'whatsapp_number',
        'bot_instance_id',
    ];

    protected $casts = [
        'is_connected_to_whatsapp' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function botInstance(): BelongsTo
    {
        return $this->belongsTo(BotInstance::class);
    }
}
