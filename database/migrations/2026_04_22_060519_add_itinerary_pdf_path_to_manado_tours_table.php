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
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->string('itinerary_pdf_path')->nullable()->after('exclusions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('itinerary_pdf_path');
        });
    }
};
