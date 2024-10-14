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
        Schema::dropIfExists('stock_piles');

        Schema::create('stock_piles', function (Blueprint $table) {
            $table->id();
            $table->string('stock_id')->nullable();
            $table->string('lgu_id')->nullable();
            $table->string('quick_response_fund')->nullable();
            $table->string('familyFood_quantity')->nullable();
            $table->string('familyFood_price')->nullable();
            $table->string('familyKits_quantity')->nullable();
            $table->string('familyKits_price')->nullable();
            $table->string('hygieneKits_quantity')->nullable();
            $table->string('hygieneKits_price')->nullable();
            $table->string('kitchenKits_quantity')->nullable();
            $table->string('kitchenKits_price')->nullable();
            $table->string('mosquitoKits_quantity')->nullable();
            $table->string('mosquitoKits_price')->nullable();
            $table->string('modularTents_quantity')->nullable();
            $table->string('modularTents_price')->nullable();
            $table->string('sleepingKits_quantity')->nullable();
            $table->string('sleepingKits_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('stock_piles');
    }
};
