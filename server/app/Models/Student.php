<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'teacher_id',
        'class_id',
        'student_id',
        'guardian_name',
        'guardian_phone',
        'notes',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(QuranClass::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignmentCompletions(): HasMany
    {
        return $this->hasMany(AssignmentCompletion::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getWeeklyAssignments(int $weekNumber = null)
    {
        $week = $weekNumber ?? now()->weekOfYear;

        // Get both individual and class assignments
        return Assignment::where(function ($query) use ($week) {
            $query->where('student_id', $this->id)
                ->orWhere('class_id', $this->class_id);
        })
            ->where('week_number', $week)
            ->where('is_active', true)
            ->with('assignmentCompletions')
            ->get();
    }

    public function getCompletionRate(): float
    {
        $totalAssignments = $this->assignmentCompletions()->count();
        if ($totalAssignments === 0) return 0;

        $completedAssignments = $this->assignmentCompletions()->where('is_completed', true)->count();
        return ($completedAssignments / $totalAssignments) * 100;
    }

    public function getProgressHistory()
    {
        return $this->assignmentCompletions()
            ->with('assignment')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
