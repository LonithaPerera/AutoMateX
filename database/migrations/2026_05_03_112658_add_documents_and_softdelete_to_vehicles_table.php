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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->date('insurance_expiry')->nullable()->after('notes');
            $table->date('registration_expiry')->nullable()->after('insurance_expiry');
            $table->date('emission_due')->nullable()->after('registration_expiry');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['insurance_expiry', 'registration_expiry', 'emission_due']);
            $table->dropSoftDeletes();
        });
    }
};
