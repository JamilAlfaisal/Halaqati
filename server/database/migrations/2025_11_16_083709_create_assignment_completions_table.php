<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->datetime('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->text('notes')->nullable(); // Teacher or student notes
            $table->text('teacher_feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5 rating from teacher
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);
            $table->index(['student_id', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_completions');
    }
};
