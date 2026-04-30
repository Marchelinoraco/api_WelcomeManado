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
            $table->string('featured_badge')->nullable()->after('is_featured');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->string('featured_badge')->nullable()->after('is_featured');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->string('featured_badge')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('featured_badge');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('featured_badge');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('featured_badge');
        });
    }
};
