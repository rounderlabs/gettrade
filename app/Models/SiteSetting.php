<?php

namespace App\Models;

use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory, SerializeDateTrait;
    protected $fillable = [
        'key',
        'value',
        'type',
        'autoload',
    ];
    protected $casts = [
        'value' => 'array',
    ];

    public static function get($key, $default = null)
    {
        return cache()->rememberForever("site_setting_$key", function () use ($key, $default) {
            return optional(
                self::where('key', $key)->first()
            )->value ?? $default;
        });
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        cache()->forget("site_setting_$key");
    }

    public static function getLevelRoiPercent(int $level): float
    {
        $levels = self::get('level_percentages', []);

        $config = collect($levels)->firstWhere('level', $level);

        return $config ? ((float) $config['percent'] / 100) : 0;
    }
}
