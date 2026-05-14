<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('insurance_doc')->nullable()->after('insurance_expiry');
            $table->string('registration_doc')->nullable()->after('registration_expiry');
            $table->string('emission_doc')->nullable()->after('emission_due');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['insurance_doc', 'registration_doc', 'emission_doc']);
        });
    }
};
