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
            $table->string('user_id')->nullable()->after('id');
            $table->string('report_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('info_graphics', function (Blueprint $table) {
            $table->dropColumn('user_id')->nullable();
            $table->dropColumn('report_id')->nullable();
        });
    }
};
