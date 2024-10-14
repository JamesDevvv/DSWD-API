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
        Schema::dropIfExists('reports');

        Schema::rename('info_graphics', 'reports');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //


        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->nullable();
            $table->string('user_id')->nullable();
            $table->string('approver_id')->nullable();
            $table->string('disaster_type')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('district_code')->nullable();
            $table->string('municipality_code')->nullable();
            $table->string('barangay_code')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::rename('reports', 'info_graphics');
    }
};
