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
        Schema::create('stock_allotments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff_details')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('stock_items')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('allotment_date');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_allotments');
    }
};
