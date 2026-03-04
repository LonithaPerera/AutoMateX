<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->integer('vehicle_year_from');
            $table->integer('vehicle_year_to');
            $table->string('part_name');
            $table->string('part_category');
            $table->string('oem_part_number');
            $table->string('alternative_part_number')->nullable();
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};