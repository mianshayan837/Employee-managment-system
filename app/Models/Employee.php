<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'shift_id',
        'name',
        'email',
        'phone',
        'profile_image',
        'designation',
        'salary',
        'joining_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getProfileImageUrlAttribute(): ?string
    {
        if (! $this->profile_image) {
            return null;
        }
        return str_starts_with($this->profile_image, 'http')
            ? $this->profile_image
            : Storage::disk('public')->url($this->profile_image);
    }
    public function usedLeaveDays(string $type): int
    {
        return (int) $this->leaveRequests()
            ->where('type', $type)
            ->where('status', 'approved')
            ->sum('days');
    }

    public function totalLeaveAllowance(string $type): int
    {
        $settings = LeaveSetting::current();

        return match ($type) {
            'annual' => $settings->annual_default,
            'sick' => $settings->sick_default,
            'casual' => $settings->casual_default,
            default => 0,
        };
    }

    public function remainingLeaveDays(string $type): int
    {
        return max(0, $this->totalLeaveAllowance($type) - $this->usedLeaveDays($type));
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('employee_code', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhereHas('department', function ($dq) use ($term) {
                  $dq->where('name', 'like', "%{$term}%");
              });
        });
    }
}