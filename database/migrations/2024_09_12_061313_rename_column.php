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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('municipality_city_code'); // Adjust the data type as needed
            $table->string('municipality_code')->nullable()->after('province_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('municipality_code');
            $table->string('municipality_city_code')->nullable()->after('province_code');
        });
    }
};
