<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->string('itinerary_pdf_path')->nullable()->after('interest_tags');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->string('itinerary_pdf_path')->nullable()->after('passport_validity');
        });
    }

    public function down(): void
    {
        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('itinerary_pdf_path');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('itinerary_pdf_path');
        });
    }
};
