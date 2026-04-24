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
        Schema::table('system_allotments', function (Blueprint $table) {
            $table->dropColumn(['monitor', 'keyboard', 'mouse', 'other_peripherals', 'ups']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_allotments', function (Blueprint $table) {
            //
        });
    }
};
