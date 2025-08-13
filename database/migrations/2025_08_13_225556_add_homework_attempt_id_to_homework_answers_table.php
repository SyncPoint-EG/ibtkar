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
        Schema::table('homework_answers', function (Blueprint $table) {
            $table->foreignId('homework_attempt_id')->after('id')->constrained('homework_attempts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_answers', function (Blueprint $table) {
            $table->dropForeign(['homework_attempt_id']);
            $table->dropColumn('homework_attempt_id');
        });
    }
};