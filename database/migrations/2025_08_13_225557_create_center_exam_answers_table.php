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
        Schema::create('center_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_exam_attempt_id')->constrained('center_exam_attempts')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('center_exam_id')->constrained('center_exams')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('center_exam_questions')->onDelete('cascade');
            $table->foreignId('option_id')->nullable()->constrained('center_exam_question_options')->onDelete('cascade');
            $table->text('essay_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_exam_answers');
    }
};
