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
        Schema::table('roles', function (Blueprint $table) {
            $table->string('local_goverment_unit')->default('0')->change();
            $table->string('emergency_operation_center')->default('0')->change();
            $table->string('regional_director')->default('0')->change();
            $table->string('local_chief_executive')->default('0')->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('local_goverment_unit')->default(false)->change();
            $table->boolean('emergency_operation_center')->default(false)->change();
            $table->boolean('regional_director')->default(false)->change();
            $table->boolean('local_chief_executive')->default(false)->change();
        });
    }
};
