<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'overtime_minutes',
        'worked_minutes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'present'   => 'success',
            'late'      => 'warning',
            'half_day'  => 'halfday',
            'absent'    => 'danger',
            'on_leave'  => 'pending',
            default     => 'success',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'half_day' => 'Half Day',
            'on_leave' => 'On Leave',
            default    => ucfirst($this->status),
        };
    }
}