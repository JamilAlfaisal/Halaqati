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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('record_type'); // 'attendance', 'behavior', 'achievement', etc.
            $table->text('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Store additional data as JSON
            $table->date('record_date');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Who created the record
            $table->timestamps();

            $table->index(['student_id', 'record_type']);
            $table->index(['record_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
