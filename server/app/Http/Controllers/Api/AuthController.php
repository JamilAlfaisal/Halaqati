<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'pin' => 'required|string|size:4|regex:/^[0-9]{4}$/',
        ]);

        // Try to find user (admin/teacher) first
        $user = User::where('phone', $request->phone)->first();

        if ($user && Hash::check($request->pin, $user->password)) {
            $token = $user->createToken('api-token')->plainTextToken;

            // Load teacher relationship if user is a teacher
            $userData = $user->load($user->isTeacher() ? 'teacher' : []);

            return response()->json([
                'user' => $userData,
                'user_type' => 'user',
                'role' => $user->role,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        // Try to find student
        $student = Student::where('phone', $request->phone)->first();

        if ($student && Hash::check($request->pin, $student->password)) {
            $token = $student->createToken('api-token')->plainTextToken;

            // Load student relationships
            $studentData = $student->load(['teacher.user', 'class']);

            return response()->json([
                'user' => $studentData,
                'user_type' => 'student',
                'role' => 'student',
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        throw ValidationException::withMessages([
            'phone' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $authenticatable = $request->user();

        if ($authenticatable instanceof User) {
            $userData = $authenticatable->load($authenticatable->isTeacher() ? 'teacher' : []);
            return response()->json([
                'user' => $userData,
                'user_type' => 'user',
                'role' => $authenticatable->role
            ]);
        }

        if ($authenticatable instanceof Student) {
            $studentData = $authenticatable->load(['teacher.user', 'class']);
            return response()->json([
                'user' => $studentData,
                'user_type' => 'student',
                'role' => 'student'
            ]);
        }

        return response()->json(['error' => 'Invalid user type'], 400);
    }
}
