<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Reference\BarangaySeeder;
use Database\Seeders\Reference\DisasterType;
use Database\Seeders\Reference\MunicipalitySeeder;
use Database\Seeders\Reference\ProvinceSeeder;
use Database\Seeders\Reference\LguAccountSeeders;
use Database\Seeders\Reference\RegionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            ProvinceSeeder::class,
            MunicipalitySeeder::class,
            BarangaySeeder::class,
            SuperAdminSeeder::class,
            DisasterType::class,
            LguAccountSeeders::class
        ]);
    }
}
