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
        Schema::table('notifications', function (Blueprint $table){
            $table->string('receiver_type')->nullable()->after('from_id');
            $table->dropColumn('user_type');
            $table->string('sender_type')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('notifications', function (Blueprint $table){
            $table->dropColumn('receiver_type');
            $table->string('user_type')->nullable()->after('type');
            $table->dropColumn('sender_type');
        });
    }
};
