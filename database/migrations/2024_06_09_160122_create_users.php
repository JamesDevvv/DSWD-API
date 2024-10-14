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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('team')->nullable();
            $table->string('fullname')->nullable();
            $table->string('age')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('province_code')->nullable();
            $table->string('municipality_city_code')->nullable();
            $table->string('barangay_code')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            

            $table->foreign('approver_id')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
