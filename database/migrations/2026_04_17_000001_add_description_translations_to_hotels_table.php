<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_ko')->nullable()->after('description_en');
            $table->text('description_zh')->nullable()->after('description_ko');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['description_en', 'description_ko', 'description_zh']);
        });
    }
};
