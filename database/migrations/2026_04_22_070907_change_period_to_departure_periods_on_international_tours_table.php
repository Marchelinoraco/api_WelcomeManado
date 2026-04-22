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
        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('period');
            $table->json('departure_periods')->nullable()->after('duration_nights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('departure_periods');
            $table->string('period')->nullable()->after('duration_nights');
        });
    }
};
