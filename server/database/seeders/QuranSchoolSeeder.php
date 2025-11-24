<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\QuranClass;
use App\Models\Assignment;
use App\Models\AssignmentCompletion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuranSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user with 4-digit PIN
        $admin = User::updateOrCreate(
            ['phone' => '+966501234567'],
            [
                'name' => 'Admin User',
                'email' => 'admin@hamidiapp.com',
                'password' => Hash::make('1234'), // 4-digit PIN
                'role' => 'admin',
                'phone' => '+966501234567',
                'date_of_birth' => '1980-01-01',
            ]
        );

        // Create teachers
        $teacherUsers = [];
        $teachers = [];

        for ($i = 1; $i <= 3; $i++) {
            $pin = str_pad($i * 1111, 4, '0', STR_PAD_LEFT); // 1111, 2222, 3333
            $teacherUser = User::create([
                'name' => "Teacher {$i}",
                'email' => "teacher{$i}@hamidiapp.com",
                'password' => Hash::make($pin), // 4-digit PIN
                'role' => 'teacher',
                'phone' => "+96650123456{$i}",
                'date_of_birth' => '1985-0' . ($i + 3) . '-01',
            ]);

            $teacher = Teacher::create([
                'user_id' => $teacherUser->id,
                'employee_id' => 'T' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'bio' => "Experienced Quran teacher with " . ($i + 5) . " years of teaching experience.",
                'specialization' => $i === 1 ? 'Tajweed' : ($i === 2 ? 'Memorization' : 'Reading'),
                'hire_date' => now()->subMonths(12 * ($i + 1)),
            ]);

            $teacherUsers[] = $teacherUser;
            $teachers[] = $teacher;
        }

        // Create classes
        $classes = [];
        foreach ($teachers as $index => $teacher) {
            $class = QuranClass::create([
                'name' => 'Class ' . chr(65 + $index), // A, B, C
                'description' => "Beginner level Quran class focused on " . ($index === 0 ? 'Tajweed' : ($index === 1 ? 'Memorization' : 'Reading')),
                'teacher_id' => $teacher->id,
                'capacity' => 15,
                'room_number' => '10' . ($index + 1),
                'schedule' => [
                    'sunday' => ['09:00', '11:00'],
                    'tuesday' => ['09:00', '11:00'],
                    'thursday' => ['09:00', '11:00'],
                ],
            ]);
            $classes[] = $class;
        }

        // Create students
        $students = collect();
        for ($i = 1; $i <= 20; $i++) {
            // Assign to teacher and class
            $teacherIndex = ($i - 1) % 3;
            $classId = $classes[$teacherIndex]->id;
            $teacherId = $teachers[$teacherIndex]->id;

            $pin = str_pad($i * 111, 4, '0', STR_PAD_LEFT); // 0111, 0222, 0333, etc.
            $student = Student::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@hamidiapp.com",
                'password' => Hash::make($pin), // 4-digit PIN
                'phone' => "+96650765432{$i}",
                'date_of_birth' => '2005-' . str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT) . '-01',
                'teacher_id' => $teacherId,
                'class_id' => $classId,
                'student_id' => 'S' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'guardian_name' => "Guardian of Student {$i}",
                'guardian_phone' => "+96650987654{$i}",
                'notes' => "Student {$i} is " . ($i % 3 === 0 ? 'excellent' : ($i % 3 === 1 ? 'good' : 'needs improvement')),
            ]);

            $students->push($student);
        }

        // Create assignments
        $assignmentTypes = ['reading', 'revision', 'memorizing'];
        $currentWeek = now()->weekOfYear;

        foreach ($teachers as $teacherIndex => $teacher) {
            // Create class assignments
            for ($week = $currentWeek - 2; $week <= $currentWeek + 1; $week++) {
                foreach ($assignmentTypes as $typeIndex => $type) {
                    $assignment = Assignment::create([
                        'title' => ucfirst($type) . " Assignment - Week {$week}",
                        'description' => "Weekly {$type} assignment for class " . chr(65 + $teacherIndex),
                        'teacher_id' => $teacher->id,
                        'class_id' => $classes[$teacherIndex]->id,
                        'type' => $type,
                        'pages' => $this->getRandomQuranPages($type),
                        'assigned_date' => now()->subWeeks($currentWeek - $week)->startOfWeek(),
                        'due_date' => now()->subWeeks($currentWeek - $week)->endOfWeek(),
                        'week_number' => $week,
                    ]);

                    // Create completion records for students in this class
                    $classStudents = $students->filter(function ($student) use ($classes, $teacherIndex) {
                        return $student->class_id === $classes[$teacherIndex]->id;
                    });

                    foreach ($classStudents as $student) {
                        $isCompleted = $week < $currentWeek ? (rand(1, 10) > 3) : false; // 70% completion for past weeks
                        $progressPercentage = $isCompleted ? 100 : rand(0, 80);

                        AssignmentCompletion::create([
                            'assignment_id' => $assignment->id,
                            'student_id' => $student->id,
                            'is_completed' => $isCompleted,
                            'completed_at' => $isCompleted ? now()->subWeeks($currentWeek - $week)->addDays(rand(1, 6)) : null,
                            'progress_percentage' => $progressPercentage,
                            'notes' => $this->getRandomStudentNote($isCompleted),
                            'teacher_feedback' => $isCompleted && rand(1, 10) > 5 ? $this->getRandomTeacherFeedback() : null,
                            'rating' => $isCompleted && rand(1, 10) > 5 ? rand(3, 5) : null,
                        ]);
                    }
                }
            }

            // Create some individual assignments
            $classStudents = $students->filter(function ($student) use ($classes, $teacherIndex) {
                return $student->class_id === $classes[$teacherIndex]->id;
            });

            foreach ($classStudents->take(3) as $student) {
                $assignment = Assignment::create([
                    'title' => 'Individual Practice - ' . $student->name,
                    'description' => 'Special individual assignment for extra practice',
                    'teacher_id' => $teacher->id,
                    'student_id' => $student->id,
                    'type' => $assignmentTypes[rand(0, 2)],
                    'pages' => $this->getRandomQuranPages($assignmentTypes[rand(0, 2)]),
                    'assigned_date' => now()->subDays(rand(1, 7)),
                    'due_date' => now()->addDays(rand(3, 10)),
                    'week_number' => $currentWeek,
                ]);

                $isCompleted = rand(1, 10) > 6;
                AssignmentCompletion::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now()->subDays(rand(0, 3)) : null,
                    'progress_percentage' => $isCompleted ? 100 : rand(20, 80),
                    'notes' => $this->getRandomStudentNote($isCompleted),
                    'teacher_feedback' => $isCompleted ? $this->getRandomTeacherFeedback() : null,
                    'rating' => $isCompleted ? rand(3, 5) : null,
                ]);
            }
        }

        $this->command->info('Quran School data seeded successfully!');
        $this->command->info('Admin: admin@hamidiapp.com / password');
        $this->command->info('Teachers: teacher1@hamidiapp.com, teacher2@hamidiapp.com, teacher3@hamidiapp.com / password');
        $this->command->info('Students (API only): student1@hamidiapp.com to student20@hamidiapp.com / password');
    }

    private function getRandomQuranPages(string $type): array
    {
        switch ($type) {
            case 'memorizing':
                // For memorization, typically 1-2 pages
                $pageCount = rand(1, 2);
                break;
            case 'revision':
                // For revision, typically 5-10 pages
                $pageCount = rand(5, 10);
                break;
            case 'reading':
                // For reading, typically 10-20 pages
                $pageCount = rand(10, 20);
                break;
            default:
                $pageCount = rand(1, 5);
        }

        $startPage = rand(1, 604 - $pageCount);
        $pages = [];
        for ($i = 0; $i < $pageCount; $i++) {
            $pages[] = $startPage + $i;
        }

        return $pages;
    }

    private function getRandomStudentNote(bool $isCompleted): string
    {
        $notes = [
            'Completed successfully',
            'Found it challenging but managed to finish',
            'Need more practice with Tajweed rules',
            'Enjoyed the assignment',
            'Had some difficulty with pronunciation',
            'Completed ahead of schedule',
            'Need help with some verses',
            'Working on improving memorization',
            'Still in progress',
            'Need more time to complete',
        ];

        return $notes[rand(0, count($notes) - 1)];
    }

    private function getRandomTeacherFeedback(): string
    {
        $feedback = [
            'Excellent work! Keep up the good effort.',
            'Good progress, but focus more on Tajweed rules.',
            'Well done! Your memorization is improving.',
            'Need to work on pronunciation. Practice more.',
            'Outstanding performance this week!',
            'Good effort, continue practicing daily.',
            'Impressive improvement from last week.',
            'Focus on proper recitation rhythm.',
            'Excellent memorization accuracy.',
            'Good work, maintain consistency.',
        ];

        return $feedback[rand(0, count($feedback) - 1)];
    }
}
