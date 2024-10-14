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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');            
            $table->string('employee_name')->nullable();
            $table->string('diviision')->nullable();
            $table->string('service')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        
           
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
