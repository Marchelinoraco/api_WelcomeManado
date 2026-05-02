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
            $table->string('location')->nullable()->after('title');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->string('location')->nullable()->after('title');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->string('location')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
