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
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->string('allotment_type')->default('Permanent')->after('quantity');
            $table->date('return_date')->nullable()->after('allotment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->dropColumn(['allotment_type', 'return_date']);
        });
    }
};
