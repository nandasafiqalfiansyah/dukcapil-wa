<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NlpConfig extends Model
{
    protected $table = 'nlp_configs';

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get a configuration value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $config = self::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return $config->value;
    }

    /**
     * Set a configuration value.
     */
    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }
}
