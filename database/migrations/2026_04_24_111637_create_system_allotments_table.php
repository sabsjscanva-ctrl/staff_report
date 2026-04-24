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
        Schema::create('system_allotments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->string('type')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('motherboard')->nullable();
            $table->string('graphic_card')->nullable();
            $table->string('monitor')->nullable();
            $table->string('keyboard')->nullable();
            $table->string('mouse')->nullable();
            $table->string('other_peripherals')->nullable();
            $table->string('ups')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('licensed_software')->nullable();
            $table->string('antivirus')->nullable();
            $table->text('installed_applications')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('staff_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_allotments');
    }
};
