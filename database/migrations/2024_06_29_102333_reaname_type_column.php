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
        //

        Schema::table('info_graphics', function (Blueprint $table) {
            // Rename the column using raw SQL
            DB::statement('ALTER TABLE info_graphics CHANGE type disaster_type VARCHAR(255) NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('info_graphics', function (Blueprint $table) {
            // Reverse the column rename using raw SQL
            DB::statement('ALTER TABLE info_graphics CHANGE type disaster_type VARCHAR(255) NULL');
        });
    }
};
