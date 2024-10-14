<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Ensure you include this for running raw SQL queries

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL for renaming columns
        DB::statement("ALTER TABLE `admins` CHANGE `diviision` `division` VARCHAR(255)");
        DB::statement("ALTER TABLE `admins` CHANGE `employee_name` `firstname` VARCHAR(255)");

        Schema::table('admins', function (Blueprint $table) {
            // Adding new columns without specifying the 'before' position
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('age')->nullable()->after('lastname');
            $table->string('birthdate')->nullable()->after('age');
            $table->string('contact')->nullable()->after('birthdate');
            $table->string('address')->nullable()->after('contact');
            $table->string('office')->nullable()->after('address');
            $table->string('group')->nullable()->after('office');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL for renaming columns back
        DB::statement("ALTER TABLE `admins` CHANGE `division` `diviision` VARCHAR(255)");
        DB::statement("ALTER TABLE `admins` CHANGE `firstname` `employee_name` VARCHAR(255)");

        Schema::table('admins', function (Blueprint $table) {
            // Dropping the newly added columns
            $table->dropColumn(['lastname', 'age', 'birthdate', 'contact', 'address', 'office']);
        });
    }
};
