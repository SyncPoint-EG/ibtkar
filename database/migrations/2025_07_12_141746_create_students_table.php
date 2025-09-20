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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('password');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->cascadeOnDelete();
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->cascadeOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->cascadeOnDelete();
            $table->foreignId('center_id')->nullable()->constrained('centers')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('stages')->cascadeOnDelete();
            $table->foreignId('grade_id')   ->constrained('grades')->cascadeOnDelete();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->cascadeOnDelete()                           ;
            $table->foreignId('education_type_id')->nullable()->constrained('education_types')->cascadeOnDelete()                           ;
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('status')->default(false);
            $table->string('verification_code')->nullable();
            $table->string('referral_code')->unique();
            $table->integer('points')->default(0);
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->foreign('referred_by')->references('id')->on('students')->onDelete('set null');
            $table->float('wallet')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
