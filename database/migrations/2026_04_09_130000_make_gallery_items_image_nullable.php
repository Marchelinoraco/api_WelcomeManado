<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gallery_items')) {
            return;
        }

        $connection = DB::connection()->getDriverName();
        if ($connection === 'mysql') {
            DB::statement('ALTER TABLE gallery_items MODIFY image_path VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('gallery_items')) {
            return;
        }

        $connection = DB::connection()->getDriverName();
        if ($connection === 'mysql') {
            DB::statement('ALTER TABLE gallery_items MODIFY image_path VARCHAR(255) NOT NULL');
        }
    }
};

