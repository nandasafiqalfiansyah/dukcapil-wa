<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_user_id',
        'assigned_to',
        'request_number',
        'service_type',
        'description',
        'status',
        'priority',
        'escalated_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'escalated_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_number)) {
                $model->request_number = self::generateRequestNumber();
            }
        });
    }

    public static function generateRequestNumber(): string
    {
        $prefix = 'REQ';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        return "{$prefix}-{$date}-{$random}";
    }

    public function whatsappUser(): BelongsTo
    {
        return $this->belongsTo(WhatsAppUser::class);
    }

    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documentValidations(): HasMany
    {
        return $this->hasMany(DocumentValidation::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeEscalated($query)
    {
        return $query->whereNotNull('escalated_at');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function isEscalated(): bool
    {
        return !is_null($this->escalated_at);
    }

    public function escalate(): void
    {
        $this->escalated_at = now();
        $this->save();
    }
}
