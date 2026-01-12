<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\QuranClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated', 'message' => 'Token expired or invalid'], 401);
        }

        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $weekNumber = $request->get('week', now()->weekOfYear);

        try {
            $data = [
                'teacher' => $teacher->load('user'),
                'stats' => [
                    'total_students' => $teacher->getTotalStudentsCount(),
                    'total_classes' => $teacher->activeClasses()->count(),
                    'weekly_assignments' => $teacher->getWeeklyAssignments($weekNumber)->count(),
                ],
                'recent_students' => $teacher->activeStudents()->limit(10)->get(),
                'active_classes' => $teacher->activeClasses()->with('students')->get(),
                'weekly_assignments' => $teacher->getWeeklyAssignments($weekNumber)->with(['student', 'class', 'assignmentCompletions'])->get(),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve dashboard data'], 500);
        };
    }

    public function students(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated', 'message' => 'Token expired or invalid'], 401);
        }

        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        try {
            $students = $teacher->activeStudents()
                ->with(['class', 'assignmentCompletions'])
                ->get();

            // Add completion rate for each student
            $studentsWithProgress = $students->map(function ($student) {
                return [
                    'student' => $student,
                    'completion_rate' => $student->getCompletionRate(),
                    'recent_assignments' => $student->assignmentCompletions()
                        ->with('assignment')
                        ->latest()
                        ->limit(5)
                        ->get()
                ];
            });

            return response()->json($studentsWithProgress);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve students data'], 500);
        }
    }

    public function studentProgress(Request $request, Student $student): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated', 'message' => 'Token expired or invalid'], 401);
        }

        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        // Ensure the student belongs to this teacher
        if ($student->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $weekNumber = $request->get('week', now()->weekOfYear);

        $data = [
            'student' => $student->load(['class']),
            'completion_rate' => $student->getCompletionRate(),
            'weekly_assignments' => $student->getWeeklyAssignments($weekNumber),
            'progress_history' => $student->getProgressHistory(),
        ];

        return response()->json($data);
    }

    public function classes(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated', 'message' => 'Token expired or invalid'], 401);
        }

        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $classes = $teacher->activeClasses()
            ->with(['students'])
            ->get()
            ->map(function ($class) {
                return [
                    'class' => $class,
                    'student_count' => $class->getStudentCount(),
                    'has_capacity' => $class->hasCapacity(),
                ];
            });

        return response()->json($classes);
    }

    public function classProgress(Request $request, QuranClass $class): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated', 'message' => 'Token expired or invalid'], 401);
        }

        if (!$user instanceof \App\Models\User || !$user->isTeacher()) {
            return response()->json(['error' => 'Teacher access required'], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        // Ensure the class belongs to this teacher
        if ($class->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $weekNumber = $request->get('week', now()->weekOfYear);

        $data = [
            'class' => $class,
            'progress' => $class->getClassProgress($weekNumber),
            'weekly_assignments' => $class->getWeeklyAssignments($weekNumber)->with('assignmentCompletions')->get(),
        ];

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with(['user', 'students', 'classes'])
            ->where('is_active', true)
            ->get();

        return response()->json($teachers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher): JsonResponse
    {
        $teacher->load(['user', 'students.user', 'classes']);

        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $request->validate([
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
        ]);

        $teacher->update($request->only(['bio', 'specialization', 'hire_date']));

        return response()->json($teacher->load('user'));
    }
}
