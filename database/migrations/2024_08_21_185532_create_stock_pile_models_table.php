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
        Schema::create('stock_piles', function (Blueprint $table) {
            $table->id();
            $table->string('lgu_id')->nullable();
            $table->string('quick_response_fund')->nullable();
            $table->string('grocery_packs')->nullable();
            $table->string('grocery_packs_pcs')->nullable();
            $table->string('non_food_item')->nullable();
            $table->string('family_kit')->nullable();
            $table->string('hygiene_kit')->nullable();
            $table->string('modular_tent')->nullable();
            $table->string('sleeping_kit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_piles');
    }
};
