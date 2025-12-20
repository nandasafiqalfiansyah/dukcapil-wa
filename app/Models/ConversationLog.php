<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_user_id',
        'message_id',
        'direction',
        'message_content',
        'message_type',
        'metadata',
        'status',
        'intent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function whatsappUser(): BelongsTo
    {
        return $this->belongsTo(WhatsAppUser::class);
    }

    public function scopeIncoming($query)
    {
        return $query->where('direction', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'outgoing');
    }

    public function scopeByIntent($query, string $intent)
    {
        return $query->where('intent', $intent);
    }
}
