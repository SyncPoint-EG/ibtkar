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
        Schema::create('center_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_exam_id')->constrained('center_exams')->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_type'); // e.g., 'true_false', 'multiple_choice', 'essay'
            $table->integer('marks');
            $table->string('image')->nullable();
            $table->text('correct_essay_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_exam_questions');
    }
};
