<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->longText('situational_overview')->nullable()->change();
            $table->longText('augmentation')->nullable()->change();
            $table->longText('remarks')->nullable()->change();
        });

        Schema::table('report_archives', function (Blueprint $table) {
            $table->longText('situational_overview')->nullable()->change();
            $table->longText('augmentation')->nullable()->change();
            $table->longText('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('situational_overview', 1000)->nullable()->change();
            $table->string('augmentation', 1000)->nullable()->change();
            $table->string('remarks', 1000)->nullable()->change();
        });

        Schema::table('report_archives', function (Blueprint $table) {
            $table->text('situational_overview')->nullable()->change();
            $table->text('augmentation')->nullable()->change();
            $table->text('remarks')->nullable()->change();
        });
    }
};
