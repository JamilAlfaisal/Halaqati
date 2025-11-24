<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuranClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'capacity',
        'room_number',
        'schedule',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'schedule' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function activeStudents(): HasMany
    {
        return $this->students()->where('is_active', true);
    }

    // Helper methods
    public function getStudentCount(): int
    {
        return $this->activeStudents()->count();
    }

    public function hasCapacity(): bool
    {
        return $this->getStudentCount() < $this->capacity;
    }

    public function getWeeklyAssignments(int $weekNumber = null): HasMany
    {
        $week = $weekNumber ?? now()->weekOfYear;
        return $this->assignments()->where('week_number', $week);
    }

    public function getClassProgress(int $weekNumber = null): array
    {
        $week = $weekNumber ?? now()->weekOfYear;
        $assignments = $this->getWeeklyAssignments($week)->get();
        $students = $this->activeStudents()->get();

        $progress = [];
        foreach ($students as $student) {
            $studentProgress = [
                'student' => $student,
                'completion_rate' => $student->getCompletionRate(),
                'weekly_assignments' => $student->getWeeklyAssignments($week)
            ];
            $progress[] = $studentProgress;
        }

        return $progress;
    }
}
