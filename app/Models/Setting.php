<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    private static $settings = [];
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'settings';
    protected $fillable = ['key', 'value'];
    public $timestamps = false;
    public static function getAll(): array
    {
        if (empty(self::$settings)) {
            self::$settings = Cache::remember('settings', 3600, function () {
                return self::all()->pluck('value', 'key')->toArray();
            });
        }
        return self::$settings;
    }
}
