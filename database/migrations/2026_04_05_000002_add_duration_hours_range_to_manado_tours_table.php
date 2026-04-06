<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->integer('duration_hours_min')->nullable()->after('duration_hours');
            $table->integer('duration_hours_max')->nullable()->after('duration_hours_min');
        });
    }

    public function down(): void
    {
        Schema::table('manado_tours', function (Blueprint $table) {
            $table->dropColumn(['duration_hours_min', 'duration_hours_max']);
        });
    }
};
