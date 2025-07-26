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
        Schema::create('homework_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['true_false', 'multiple_choice', 'essay']);
            $table->integer('marks')->default(1);
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_questions');
    }
};
