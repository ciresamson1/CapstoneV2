<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {

            $table->id();

            // Project title
            $table->string('name');

            // Project description
            $table->text('description')->nullable();

            // Project start date
            $table->date('start_date');

            // Project deadline
            $table->date('end_date');

            // Current project status
            $table->string('status')->default('planning');

            // Assigned project manager
            $table->foreignId('manager_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // User who created the project
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};