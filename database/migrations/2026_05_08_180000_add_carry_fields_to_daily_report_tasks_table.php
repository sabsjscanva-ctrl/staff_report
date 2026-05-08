<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->boolean('is_carry')->default(false)->after('description');
            $table->string('previous_time')->nullable()->after('is_carry');
        });
    }

    public function down(): void
    {
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->dropColumn(['is_carry', 'previous_time']);
        });
    }
};
