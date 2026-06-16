<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->string('status')->default('Allotted')->after('remark');
            $table->date('returned_date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('returned_date');
        });
    }
};
