<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'department_id',
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

    
    public function getProfileImageUrlAttribute(): ?string
    {
        return $this->profile_image
            ? Storage::disk('public')->url($this->profile_image)
            : null;
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
