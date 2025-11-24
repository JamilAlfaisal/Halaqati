<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'bio',
        'specialization',
        'hire_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(QuranClass::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeStudents(): HasMany
    {
        return $this->students()->where('is_active', true);
    }

    public function activeClasses(): HasMany
    {
        return $this->classes()->where('is_active', true);
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getTotalStudentsCount(): int
    {
        return $this->activeStudents()->count();
    }

    public function getWeeklyAssignments(int $weekNumber = null): HasMany
    {
        $week = $weekNumber ?? now()->weekOfYear;
        return $this->assignments()->where('week_number', $week);
    }
}
