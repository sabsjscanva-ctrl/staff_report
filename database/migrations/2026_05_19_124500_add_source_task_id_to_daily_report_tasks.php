<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('source_task_id')->nullable()->after('daily_report_id');
            
            $table->foreign('source_task_id')
                  ->references('id')
                  ->on('daily_report_tasks')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->dropForeign(['source_task_id']);
            $table->dropColumn('source_task_id');
        });
    }
};
