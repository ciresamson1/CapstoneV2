<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {

            $table->id();

            // project this task belongs to
            $table->foreignId('project_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // task title
            $table->string('title');

            // task details
            $table->text('description')->nullable();

            // user assigned to the task
            $table->foreignId('assigned_to')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // task status
            $table->string('status')->default('pending');

            // start date
            $table->date('start_date');

            // deadline
            $table->date('due_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};