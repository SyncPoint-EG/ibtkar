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
        Schema::table('semisters', function (Blueprint $table) {
            $table->unsignedTinyInteger('start_month')->after('name')->nullable();
            $table->unsignedTinyInteger('start_day')->after('start_month')->nullable();
            $table->unsignedTinyInteger('end_month')->after('start_day')->nullable();
            $table->unsignedTinyInteger('end_day')->after('end_month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semisters', function (Blueprint $table) {
            $table->dropColumn(['start_month', 'start_day', 'end_month', 'end_day']);
        });
    }
};
