<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->integer('duration_hours')->nullable()->after('duration_nights');
        });
    }

    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn('duration_hours');
        });
    }
};
