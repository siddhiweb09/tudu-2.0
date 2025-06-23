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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('task_lists')->nullable();
            $table->string('department')->nullable();
            $table->string('priority')->nullable();
            $table->string('frequency')->nullable(); // date of joining
            $table->longText('frequency_duration')->nullable(); // date of birth
            $table->string('reminders')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('status')->nullable();
            $table->string('final_status')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('ratings')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('delegated_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->string('delegate_task_id');
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('department')->nullable();
            $table->string('priority')->nullable();
            $table->string('frequency')->nullable(); // date of joining
            $table->longText('frequency_duration')->nullable(); // date of birth
            $table->string('reminders')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('status')->nullable();
            $table->string('final_status')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('ratings')->nullable();
            $table->date('due_date')->nullable();
            $table->longText('visible_to')->nullable();
            $table->timestamps();
        });

        Schema::create('personal_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('frequency')->nullable(); // date of joining
            $table->longText('frequency_duration')->nullable(); // date of birth
            $table->string('reminders')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('status')->nullable();
            $table->longText('notes')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('task_medias', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->string('category')->nullable();
            $table->longText('file_name')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('task_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->date('assigned_date')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('completion_date')->nullable();
            $table->string('frequency')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all');
    }
};
