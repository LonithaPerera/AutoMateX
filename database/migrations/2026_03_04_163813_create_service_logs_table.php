<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('service_type');
            $table->date('service_date');
            $table->integer('mileage_at_service');
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('garage_name')->nullable();
            $table->text('notes')->nullable();
            $table->enum('type', ['maintenance', 'repair', 'inspection'])
                  ->default('maintenance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_logs');
    }
};