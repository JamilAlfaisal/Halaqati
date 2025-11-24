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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade'); // null for class assignments
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade'); // null for individual assignments
            $table->enum('type', ['reading', 'revision', 'memorizing']);
            $table->json('pages'); // Store Quran pages as JSON array
            $table->date('assigned_date');
            $table->date('due_date')->nullable();
            $table->integer('week_number')->nullable(); // For weekly tracking
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['teacher_id', 'week_number']);
            $table->index(['student_id', 'assigned_date']);
            $table->index(['class_id', 'assigned_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
