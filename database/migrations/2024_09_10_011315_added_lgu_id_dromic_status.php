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
        // add lce_id on reports and report_archives
        Schema::table('reports', function (Blueprint $table) {
            $table->string('lce_id')->nullable()->after('dromic_status');
        });
        Schema::table('report_archives', function (Blueprint $table) {
            $table->string('lce_id')->nullable()->after('dromic_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // remove lce_id on reports and report_archives
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('lce_id');
        });
        Schema::table('report_archives', function (Blueprint $table) {
            $table->dropColumn('lce_id');
        });
    }
};
