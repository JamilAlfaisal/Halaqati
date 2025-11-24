<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'is_completed',
        'completed_at',
        'progress_percentage',
        'notes',
        'teacher_feedback',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'progress_percentage' => 'integer',
            'rating' => 'integer',
        ];
    }

    // Relationships
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Helper methods
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }

    public function updateProgress(int $percentage, string $notes = null): void
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'notes' => $notes ?? $this->notes,
            'is_completed' => $percentage >= 100,
            'completed_at' => $percentage >= 100 ? now() : null,
        ]);
    }

    public function addTeacherFeedback(string $feedback, int $rating = null): void
    {
        $this->update([
            'teacher_feedback' => $feedback,
            'rating' => $rating,
        ]);
    }

    public function getStatusText(): string
    {
        if ($this->is_completed) {
            return 'Completed';
        }

        if ($this->progress_percentage > 0) {
            return "In Progress ({$this->progress_percentage}%)";
        }

        return 'Not Started';
    }

    public function getRatingStars(): string
    {
        if (!$this->rating) return 'No rating';

        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
