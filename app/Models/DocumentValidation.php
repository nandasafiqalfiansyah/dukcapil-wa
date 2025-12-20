<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'validated_by',
        'document_type',
        'file_path',
        'original_filename',
        'validation_status',
        'validation_results',
        'validation_notes',
        'validated_at',
        'metadata',
    ];

    protected $casts = [
        'validation_results' => 'array',
        'validated_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function scopePending($query)
    {
        return $query->where('validation_status', 'pending');
    }

    public function scopeValid($query)
    {
        return $query->where('validation_status', 'valid');
    }

    public function scopeInvalid($query)
    {
        return $query->where('validation_status', 'invalid');
    }

    public function markAsValid(User $validator, ?string $notes = null): void
    {
        $this->update([
            'validation_status' => 'valid',
            'validated_by' => $validator->id,
            'validated_at' => now(),
            'validation_notes' => $notes,
        ]);
    }

    public function markAsInvalid(User $validator, string $notes): void
    {
        $this->update([
            'validation_status' => 'invalid',
            'validated_by' => $validator->id,
            'validated_at' => now(),
            'validation_notes' => $notes,
        ]);
    }
}
