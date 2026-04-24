<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_prices', function (Blueprint $table) {
            $table->string('type', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tour_prices', function (Blueprint $table) {
            $table->enum('type', ['adult_twin', 'child_bed', 'child_no_bed'])->change();
        });
    }
};
