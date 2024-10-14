<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\MunicipalityModel;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path("seeders/Reference/JSON/city_district.json"));
        $data = json_decode($json, true);

        foreach ($data['city_district'] as $region) {
            MunicipalityModel::create([
                'code' => $region['code'],
                'province_code' => $region['Municipality_City_code'],
                'name' => $region['name']
            ]);
        }
    }
}
