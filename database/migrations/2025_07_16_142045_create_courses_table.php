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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('year');
            $table->foreignId('teacher_id')->nullable()->constrained();
            $table->foreignId('education_type_id')->nullable()->constrained();
            $table->foreignId('stage_id')->nullable()->constrained();
            $table->foreignId('grade_id')->nullable()->constrained();
            $table->foreignId('division_id')->nullable()->constrained();
            $table->foreignId('semister_id')->nullable()->constrained();
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->decimal('price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
