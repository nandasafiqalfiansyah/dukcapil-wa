<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppUser extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_users';

    protected $fillable = [
        'phone_number',
        'name',
        'nik',
        'is_verified',
        'verified_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function conversationLogs(): HasMany
    {
        return $this->hasMany(ConversationLog::class);
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
