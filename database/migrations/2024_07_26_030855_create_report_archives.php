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
        Schema::dropIfExists('report_archives');
        Schema::create('report_archives', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('created_by')->nullable();
            $table->string('lgu_id')->nullable();
            $table->string('incident_code')->nullable();
            $table->string('incident_date')->nullable();
            $table->string('disaster_type')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('district_code')->nullable();
            $table->string('municipality_code')->nullable();
            $table->string('barangay_code')->nullable();
            $table->string('no_families')->nullable();
            $table->string('no_individual')->nullable();
            $table->string('dead')->nullable();
            $table->string('injured')->nullable();
            $table->string('missing')->nullable();
            $table->string('residential')->nullable();
            $table->string('commercial')->nullable();
            $table->string('mix')->nullable();
            $table->string('total_damage')->nullable();
            $table->string('partial_damage')->nullable();
            $table->string('situational_overview', 1000)->nullable();
            $table->string('augmentation', 1000)->nullable();
            $table->string('remarks', 1000)->nullable();
            $table->string('progress_status')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_archives');
    }
};