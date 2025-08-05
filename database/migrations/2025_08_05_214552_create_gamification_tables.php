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
        Schema::create('action_points', function (Blueprint $table) {
            $table->id();
            $table->string('action_name')->unique();
            $table->integer('points');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('reward_points', function (Blueprint $table) {
            $table->id();
            $table->string('reward_name')->unique();
            $table->integer('points_cost');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_points');
        Schema::dropIfExists('reward_points');
        
    }
};
