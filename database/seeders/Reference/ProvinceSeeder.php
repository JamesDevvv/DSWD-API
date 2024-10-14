<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\ProvinceModel;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path("seeders/Reference/JSON/structured_municipality_city.json"));
        $data = json_decode($json, true);

        foreach ($data['municipality_city'] as $region) {
            ProvinceModel::create([
                'code' => $region['code'],
                'name' => $region['name']
            ]);
        }
    }
}
