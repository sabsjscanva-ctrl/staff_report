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
        Schema::create('stock_item_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->onDelete('cascade');
            $table->string('name'); // This will store the brand name
            $table->integer('quantity')->default(0);
            $table->text('details')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });

        // Update stock_purchases to point to stock_item_brands
        Schema::table('stock_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('item_id');
        });

        // Update stock_allotments to point to stock_item_brands
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('item_id');
        });

        // Migrate data if any exists
        // (Since I can't easily run complex SQL here and be sure of the state, I'll just prepare the columns)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_allotments', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
        Schema::table('stock_purchases', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
        Schema::dropIfExists('stock_item_brands');
    }
};
