<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuranClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuranClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = QuranClass::with(['teacher.user', 'students'])
            ->where('is_active', true);

        // Filter by teacher if user is a teacher
        if ($user instanceof \App\Models\User && $user->isTeacher()) {
            $query->where('teacher_id', $user->teacher->id);
        }

        $classes = $query->get()->map(function ($class) {
            return [
                'class' => $class,
                'student_count' => $class->getStudentCount(),
                'has_capacity' => $class->hasCapacity(),
            ];
        });

        return response()->json($classes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:100',
            'room_number' => 'nullable|string|max:50',
            'schedule' => 'nullable|array',
        ]);

        $user = $request->user();
        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Only teachers can create classes'], 403);
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $class = QuranClass::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $teacher->id,
            'capacity' => $request->capacity,
            'room_number' => $request->room_number,
            'schedule' => $request->schedule,
        ]);

        return response()->json(
            $class->load(['teacher.user', 'students.user']),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(QuranClass $quranClass): JsonResponse
    {
        $quranClass->load(['teacher.user', 'students.user', 'assignments']);

        $data = [
            'class' => $quranClass,
            'student_count' => $quranClass->getStudentCount(),
            'has_capacity' => $quranClass->hasCapacity(),
            'recent_assignments' => $quranClass->assignments()
                ->with('assignmentCompletions')
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuranClass $quranClass): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $quranClass->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'sometimes|required|integer|min:1|max:100',
            'room_number' => 'nullable|string|max:50',
            'schedule' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $quranClass->update($request->only([
            'name',
            'description',
            'capacity',
            'room_number',
            'schedule',
            'is_active'
        ]));

        return response()->json(
            $quranClass->load(['teacher.user', 'students.user'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuranClass $quranClass): JsonResponse
    {
        $teacher = request()->user()->teacher;

        if (!$teacher || $quranClass->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $quranClass->update(['is_active' => false]);

        return response()->json(['message' => 'Class deactivated successfully']);
    }

    /**
     * Add a student to the class
     */
    public function addStudent(Request $request, QuranClass $quranClass): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $quranClass->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::find($request->student_id);

        // Check if class has capacity
        if (!$quranClass->hasCapacity()) {
            return response()->json(['error' => 'Class is at full capacity'], 422);
        }

        // Check if student belongs to the same teacher
        if ($student->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Student does not belong to this teacher'], 422);
        }

        $student->update(['class_id' => $quranClass->id]);

        return response()->json([
            'message' => 'Student added to class successfully',
            'class' => $quranClass->load(['students.user']),
        ]);
    }

    /**
     * Remove a student from the class
     */
    public function removeStudent(Request $request, QuranClass $quranClass, Student $student): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $quranClass->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($student->class_id !== $quranClass->id) {
            return response()->json(['error' => 'Student is not in this class'], 422);
        }

        $student->update(['class_id' => null]);

        return response()->json([
            'message' => 'Student removed from class successfully',
            'class' => $quranClass->load(['students.user']),
        ]);
    }

    /**
     * Get class progress for a specific week
     */
    public function weeklyProgress(Request $request, QuranClass $quranClass): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher || $quranClass->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $weekNumber = $request->get('week', now()->weekOfYear);

        $progress = $quranClass->getClassProgress($weekNumber);

        return response()->json([
            'class' => $quranClass,
            'week_number' => $weekNumber,
            'progress' => $progress,
        ]);
    }
}
