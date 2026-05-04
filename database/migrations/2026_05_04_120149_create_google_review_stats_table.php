<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('google_review_stats', function (Blueprint $table) {
            $table->id();
            $table->decimal('rating', 2, 1)->default(4.9); // e.g., 4.9
            $table->integer('review_count')->default(39); // e.g., 39
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
        });

        // Insert default data
        DB::table('google_review_stats')->insert([
            'rating' => 4.9,
            'review_count' => 39,
            'last_updated' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_review_stats');
    }
};
