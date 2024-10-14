<?php

namespace Database\Seeders\Reference;

use App\Models\AdminModel;
use File;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LguAccountSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountjson = File::get(database_path("seeders/Imports/LGU-Accounts.json"));
        $accountdata = json_decode($accountjson, true);

        foreach ($accountdata as $data) {
            AdminModel::create([
                'role_id' => $data['role_id'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
