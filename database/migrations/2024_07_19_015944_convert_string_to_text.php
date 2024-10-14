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
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'situational_overview')) {
                $table->text('situational_overview')->nullable();
            }

            if (!Schema::hasColumn('reports', 'augmentation')) {
                $table->text('augmentation')->nullable();
            }

            if (!Schema::hasColumn('reports', 'remarks')) {
                $table->text('remarks')->nullable();
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
