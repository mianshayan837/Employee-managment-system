<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function durationInMinutes(): int
    {
        return Carbon::parse($this->start_time)->diffInMinutes(Carbon::parse($this->end_time));
    }

   
    public function halfDayThresholdMinutes(): int
    {
        return (int) ($this->durationInMinutes() / 2);
    }

    
    public function timeRangeLabel(): string
    {
        return Carbon::parse($this->start_time)->format('h:i A').' - '.Carbon::parse($this->end_time)->format('h:i A');
    }
}