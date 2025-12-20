<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoReplyConfig extends Model
{
    protected $fillable = [
        'trigger',
        'response',
        'is_active',
        'priority',
        'case_sensitive',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'case_sensitive' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Scope to get only active auto-replies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by priority (higher first).
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }
}
