<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('itinerary_pdf_path');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('itinerary_pdf_path');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('itinerary_pdf_path');
        });
    }

    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
