<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'student_id',
        'class_id',
        'type',
        'pages',
        'assigned_date',
        'due_date',
        'week_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'pages' => 'array',
            'assigned_date' => 'date',
            'due_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(QuranClass::class, 'class_id');
    }

    public function assignmentCompletions(): HasMany
    {
        return $this->hasMany(AssignmentCompletion::class);
    }

    // Helper methods
    public function isIndividual(): bool
    {
        return !is_null($this->student_id);
    }

    public function isClassAssignment(): bool
    {
        return !is_null($this->class_id);
    }

    public function getTargetStudents()
    {
        if ($this->isIndividual()) {
            return collect([$this->student]);
        }

        if ($this->isClassAssignment()) {
            return $this->class->activeStudents;
        }

        return collect();
    }

    public function getPagesText(): string
    {
        if (empty($this->pages)) return 'No pages assigned';

        $pages = $this->pages;
        if (count($pages) === 1) {
            return "Page {$pages[0]}";
        }

        return "Pages " . implode(', ', $pages);
    }

    public function getCompletionForStudent(Student $student): ?AssignmentCompletion
    {
        return $this->assignmentCompletions()->where('student_id', $student->id)->first();
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }

    public function getCompletionRate(): float
    {
        $targetStudents = $this->getTargetStudents();
        if ($targetStudents->isEmpty()) return 0;

        $completed = $this->assignmentCompletions()->where('is_completed', true)->count();
        return ($completed / $targetStudents->count()) * 100;
    }
}
