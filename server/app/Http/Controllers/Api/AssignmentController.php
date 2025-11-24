<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentCompletion;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Assignment::with(['teacher.user', 'student.user', 'class', 'assignmentCompletions'])
            ->where('is_active', true);

        // Filter by teacher if user is a teacher
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->teacher->id);
        }

        // Filter by student if user is a student
        if ($user instanceof \App\Models\Student) {
            $query->where(function ($q) use ($user) {
                $q->where('student_id', $user->id)
                    ->orWhere('class_id', $user->class_id);
            });
        } elseif ($user instanceof \App\Models\User && $user->isStudent()) {
            // This case should not happen anymore since students don't have User records
            return response()->json(['error' => 'Invalid access'], 403);
        }

        // Additional filters
        if ($request->has('week_number')) {
            $query->where('week_number', $request->week_number);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $assignments = $query->orderBy('assigned_date', 'desc')->get();

        return response()->json($assignments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
            'class_id' => 'nullable|exists:classes,id',
            'type' => 'required|in:reading,revision,memorizing',
            'pages' => 'required|array|min:1',
            'pages.*' => 'required|integer|min:1|max:604', // Quran has 604 pages
            'assigned_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:assigned_date',
            'week_number' => 'nullable|integer|min:1|max:53',
        ]);

        // Ensure either student_id or class_id is provided, but not both
        if (!$request->student_id && !$request->class_id) {
            return response()->json(['error' => 'Either student_id or class_id must be provided'], 422);
        }

        if ($request->student_id && $request->class_id) {
            return response()->json(['error' => 'Cannot assign to both individual student and class'], 422);
        }

        $user = $request->user();
        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Only teachers can create assignments'], 403);
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        DB::beginTransaction();
        try {
            $assignment = Assignment::create([
                'title' => $request->title,
                'description' => $request->description,
                'teacher_id' => $teacher->id,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'type' => $request->type,
                'pages' => $request->pages,
                'assigned_date' => $request->assigned_date,
                'due_date' => $request->due_date,
                'week_number' => $request->week_number ?? now()->weekOfYear,
            ]);

            // Create assignment completion records for target students
            $targetStudents = $assignment->getTargetStudents();

            foreach ($targetStudents as $student) {
                AssignmentCompletion::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'progress_percentage' => 0,
                ]);
            }

            DB::commit();

            return response()->json(
                $assignment->load(['teacher.user', 'student.user', 'class', 'assignmentCompletions']),
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create assignment'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment): JsonResponse
    {
        $assignment->load([
            'teacher.user',
            'student.user',
            'class.students.user',
            'assignmentCompletions.student.user'
        ]);

        $data = [
            'assignment' => $assignment,
            'completion_rate' => $assignment->getCompletionRate(),
            'target_students' => $assignment->getTargetStudents(),
            'is_overdue' => $assignment->isOverdue(),
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $assignment->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:reading,revision,memorizing',
            'pages' => 'sometimes|required|array|min:1',
            'pages.*' => 'required|integer|min:1|max:604',
            'due_date' => 'nullable|date|after_or_equal:assigned_date',
            'is_active' => 'sometimes|boolean',
        ]);

        $assignment->update($request->only([
            'title',
            'description',
            'type',
            'pages',
            'due_date',
            'is_active'
        ]));

        return response()->json(
            $assignment->load(['teacher.user', 'student.user', 'class', 'assignmentCompletions'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment): JsonResponse
    {
        $user = request()->user();
        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher || $assignment->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $assignment->update(['is_active' => false]);

        return response()->json(['message' => 'Assignment deactivated successfully']);
    }

    public function addTeacherFeedback(Request $request, Assignment $assignment, Student $student): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $assignment->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'feedback' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $completion = AssignmentCompletion::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$completion) {
            return response()->json(['error' => 'Assignment completion not found'], 404);
        }

        $completion->addTeacherFeedback($request->feedback, $request->rating);

        return response()->json([
            'message' => 'Feedback added successfully',
            'completion' => $completion->load(['assignment', 'student.user'])
        ]);
    }
}
