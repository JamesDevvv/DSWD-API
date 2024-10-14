<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\BarangayModel;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path("seeders/Reference/JSON/structured_barangays.json"));
        $data = json_decode($json, true);

        foreach ($data['barangay'] as $region) {
            BarangayModel::create([
                'code' => $region['code'],
                'municipality_code' => $region['city_district_code'],
                'name' => $region['name']
            ]);
        }
    }
}
