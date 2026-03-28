<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_report_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_report_id');
            $table->string('task_title');
            $table->text('description')->nullable();
            $table->enum('status', ['completed', 'in_progress', 'pending', 'paused'])->default('pending');
            $table->string('time_spend')->nullable();
            $table->timestamps();

            $table->foreign('daily_report_id')->references('id')->on('daily_reports')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_report_tasks');
    }
};
