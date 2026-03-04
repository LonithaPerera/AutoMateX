<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->date('date');
            $table->decimal('liters', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->integer('km_reading');
            $table->decimal('km_per_liter', 8, 2)->nullable();
            $table->string('fuel_station')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};