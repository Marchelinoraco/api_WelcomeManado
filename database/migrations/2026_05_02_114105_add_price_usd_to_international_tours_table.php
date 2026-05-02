<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('international_tours', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('base_price');
        });
    }

    public function down(): void
    {
        Schema::table('international_tours', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });
    }
};
