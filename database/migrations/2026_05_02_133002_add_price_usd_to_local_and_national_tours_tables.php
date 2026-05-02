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
            $table->decimal('price_usd', 10, 2)->nullable()->after('base_price');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('base_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });

        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });
    }
};
