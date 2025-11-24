<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\AssignmentCompletion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $student = $request->user();

        if (!$student instanceof \App\Models\Student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        $weekNumber = $request->get('week', now()->weekOfYear);

        $data = [
            'student' => $student->load(['teacher.user', 'class']),
            'stats' => [
                'completion_rate' => $student->getCompletionRate(),
                'total_assignments' => $student->assignmentCompletions()->count(),
                'completed_assignments' => $student->assignmentCompletions()->where('is_completed', true)->count(),
                'pending_assignments' => $student->assignmentCompletions()->where('is_completed', false)->count(),
            ],
            'weekly_assignments' => $student->getWeeklyAssignments($weekNumber),
            'recent_progress' => $student->assignmentCompletions()
                ->with('assignment')
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return response()->json($data);
    }

    public function assignments(Request $request): JsonResponse
    {
        $student = $request->user();

        if (!$student instanceof \App\Models\Student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        $weekNumber = $request->get('week');
        $status = $request->get('status'); // 'completed', 'pending', 'overdue'

        $query = Assignment::where(function ($q) use ($student) {
            $q->where('student_id', $student->id)
                ->orWhere('class_id', $student->class_id);
        })->where('is_active', true);

        if ($weekNumber) {
            $query->where('week_number', $weekNumber);
        }

        $assignments = $query->with(['assignmentCompletions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->orderBy('assigned_date', 'desc')
            ->get();

        // Filter by status if provided
        if ($status) {
            $assignments = $assignments->filter(function ($assignment) use ($student, $status) {
                $completion = $assignment->getCompletionForStudent($student);

                return match ($status) {
                    'completed' => $completion && $completion->is_completed,
                    'pending' => !$completion || !$completion->is_completed,
                    'overdue' => $assignment->isOverdue() && (!$completion || !$completion->is_completed),
                    default => true
                };
            })->values();
        }

        return response()->json($assignments);
    }

    public function markAssignmentComplete(Request $request, Assignment $assignment): JsonResponse
    {
        $student = $request->user();

        if (!$student instanceof \App\Models\Student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        // Check if assignment belongs to student
        if ($assignment->student_id !== $student->id && $assignment->class_id !== $student->class_id) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $completion = AssignmentCompletion::firstOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'progress_percentage' => 0,
                'notes' => $request->notes,
            ]
        );

        $completion->markAsCompleted();
        if ($request->notes) {
            $completion->update(['notes' => $request->notes]);
        }

        return response()->json([
            'message' => 'Assignment marked as complete',
            'completion' => $completion->load('assignment')
        ]);
    }

    public function updateAssignmentProgress(Request $request, Assignment $assignment): JsonResponse
    {
        $student = $request->user();

        if (!$student instanceof \App\Models\Student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        // Check if assignment belongs to student
        if ($assignment->student_id !== $student->id && $assignment->class_id !== $student->class_id) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $completion = AssignmentCompletion::firstOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'progress_percentage' => 0,
            ]
        );

        $completion->updateProgress($request->progress_percentage, $request->notes);

        return response()->json([
            'message' => 'Progress updated successfully',
            'completion' => $completion->load('assignment')
        ]);
    }

    public function progressHistory(Request $request): JsonResponse
    {
        $student = $request->user();

        if (!$student instanceof \App\Models\Student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        $history = $student->getProgressHistory();

        return response()->json($history);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Student::with(['teacher.user', 'class'])
            ->where('is_active', true);

        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->get();

        return response()->json($students);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): JsonResponse
    {
        $student->load(['teacher.user', 'class', 'assignmentCompletions.assignment']);

        return response()->json([
            'student' => $student,
            'completion_rate' => $student->getCompletionRate(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $request->validate([
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $student->update($request->only(['guardian_name', 'guardian_phone', 'notes']));

        return response()->json($student->load(['teacher.user', 'class']));
    }
}
