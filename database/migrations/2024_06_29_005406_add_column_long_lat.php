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
            $table->string('longitude')->nullable()->after('end');
            $table->string('latitude')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('info_graphics', function (Blueprint $table) {
            $table->dropColumn('longitude')->nullable();
            $table->dropColumn('latitude')->nullable();
        });
    }
};
