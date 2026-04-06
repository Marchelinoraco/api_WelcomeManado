<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->json('interest_tags')->nullable()->after('terms_conditions');
        });
    }

    public function down(): void
    {
        Schema::table('indonesia_destinations', function (Blueprint $table) {
            $table->dropColumn('interest_tags');
        });
    }
};
