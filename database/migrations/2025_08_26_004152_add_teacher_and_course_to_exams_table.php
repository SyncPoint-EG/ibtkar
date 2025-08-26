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
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['teacher_id', 'course_id']);
            $table->foreignId('lesson_id')->nullable(false)->change();
        });
    }
};