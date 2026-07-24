<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveSetting extends Model
{
    protected $fillable = [
        'annual_default',
        'sick_default',
        'casual_default',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'annual_default' => 14,
            'sick_default' => 10,
            'casual_default' => 7,
        ]);
    }
}