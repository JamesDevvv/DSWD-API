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
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'situational_overview')) {
                $table->string('situational_overview', 1000)->nullable();
            }

            if (!Schema::hasColumn('reports', 'augmentation')) {
                $table->string('augmentation', 1000)->nullable();
            }

            if (!Schema::hasColumn('reports', 'remarks')) {
                $table->string('remarks', 1000)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'situational_overview')) {
                $table->dropColumn('situational_overview');
            }

            if (Schema::hasColumn('reports', 'augmentation')) {
                $table->dropColumn('augmentation');
            }

            if (Schema::hasColumn('reports', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
