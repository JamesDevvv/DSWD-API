<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\DisasterModel;
use Illuminate\Database\Seeder;

class DisasterType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['fire', 'flood', 'earthquake', 'landslide', 'others'];

        foreach ($data as $d) {
            DisasterModel::create([
                'name' => $d
            ]);
        }
    }
}
