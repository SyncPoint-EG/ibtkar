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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('stage_id')->nullable()->after('student_id')->constrained()->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->after('stage_id')->constrained()->nullOnDelete();
            $table->foreignId('grade_plan_id')->nullable()->after('grade_id')->constrained()->nullOnDelete();
            $table->string('plan_type')->nullable()->after('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropForeign(['grade_id']);
            $table->dropForeign(['grade_plan_id']);
            $table->dropColumn(['stage_id', 'grade_id', 'grade_plan_id', 'plan_type']);
        });
    }
};
