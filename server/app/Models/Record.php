<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_type',
        'title',
        'description',
        'metadata',
        'record_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'record_date' => 'date',
        ];
    }

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getRecordTypeColor(): string
    {
        return match ($this->record_type) {
            'attendance' => 'blue',
            'behavior' => 'yellow',
            'achievement' => 'green',
            'assignment' => 'purple',
            'assessment' => 'orange',
            default => 'gray'
        };
    }

    public function getRecordTypeIcon(): string
    {
        return match ($this->record_type) {
            'attendance' => '📅',
            'behavior' => '😊',
            'achievement' => '🏆',
            'assignment' => '📝',
            'assessment' => '📊',
            default => '📄'
        };
    }
}
