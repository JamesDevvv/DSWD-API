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
        Schema::create('role_permisions', function (Blueprint $table) {
            $table->id();
            $table->string('feature_name')->nullable();
            $table->string('role_id')->nullable();
            $table->string('create')->nullable();
            $table->string('view')->nullable();
            $table->string('modify')->nullable();
            $table->string('delete')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permision');
    }
};
