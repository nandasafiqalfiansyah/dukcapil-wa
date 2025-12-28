<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotInstance extends Model
{
    protected $fillable = [
        'bot_id',
        'name',
        'status',
        'phone_number',
        'platform',
        'qr_code',
        'last_connected_at',
        'last_disconnected_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'last_connected_at' => 'datetime',
        'last_disconnected_at' => 'datetime',
    ];

    /**
     * Get conversation logs for this bot instance.
     */
    public function conversationLogs(): HasMany
    {
        return $this->hasMany(ConversationLog::class, 'bot_instance_id');
    }

    /**
     * Check if bot is connected (for Business API, this means it's active and configured).
     */
    public function isConnected(): bool
    {
        return $this->status === 'connected' && $this->is_active;
    }

    /**
     * Check if bot needs configuration.
     */
    public function needsConfiguration(): bool
    {
        return in_array($this->status, ['not_initialized', 'disconnected']);
    }

    /**
     * Check if bot needs QR code scanning.
     */
    public function needsQrScan(): bool
    {
        return $this->status === 'qr_generated';
    }

    /**
     * Scope to get only active bots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only connected bots.
     */
    public function scopeConnected($query)
    {
        return $query->where('status', 'connected');
    }
}
