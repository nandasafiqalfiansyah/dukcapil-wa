<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsTrainingData extends Model
{
    protected $fillable = [
        'intent',
        'pattern',
        'response',
        'keywords',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }
}
