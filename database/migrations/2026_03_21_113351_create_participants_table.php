<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('prefix');
            $table->string('full_name');
            $table->string('degree');
            $table->string('dignity');
            $table->string('lodge_name');
            $table->unsignedInteger('lodge_number');
            $table->string('orient');
            $table->string('email');
            $table->string('phone', 20);
            $table->unsignedInteger('friday_dinner_count')->default(0);
            $table->unsignedInteger('symposium_lunch_count')->default(0);
            $table->boolean('ritual_participation')->default(false);
            $table->unsignedInteger('ball_count')->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
