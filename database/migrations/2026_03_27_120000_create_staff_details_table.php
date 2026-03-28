<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('f_name');
            $table->date('dob');
            $table->string('mobile', 10);
            $table->date('doj');
            $table->unsignedBigInteger('dept_id');
            $table->string('designation');
            $table->text('address');
            $table->unsignedBigInteger('office_id');
            $table->string('photo')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->date('left_date')->nullable();
            $table->timestamps();

            $table->foreign('dept_id')->references('id')->on('departments')->onDelete('restrict');
            $table->foreign('office_id')->references('id')->on('office_details')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_details');
    }
};
