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
        Schema::table('reports',function (Blueprint $table){
            $table->string('dromic_status')->nullable()->after('progress_status');

        });

        Schema::table('report_archives',function (Blueprint $table){
            $table->string('dromic_status')->nullable()->after('progress_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('reports',function (Blueprint $table){
            $table->dropColumn('dromic_status')->nullable();

        });

        Schema::table('report_archives',function (Blueprint $table){
            $table->dropColumn('dromic_status')->nullable();
        });
    }
};
