<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\Reference\RoleModel;
use App\Models\Reference\RolePermissionModel;
use Illuminate\Database\Seeder;
use File;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rolejson = File::get(database_path("seeders/Imports/Role.json"));
        $roledatas = json_decode($rolejson, true);

        foreach($roledatas as $roledata)
        {
        $role = RoleModel::create([
            'name' => $roledata['name'],
            'description' => $roledata['description'],
            'local_goverment_unit' => $roledata['local_goverment_unit'],
            'emergency_operation_center' => $roledata['emergency_operation_center'],
            'regional_director' => $roledata['regional_director'],
            'local_chief_executive' => $roledata['local_chief_executive'],
        ]);

        $permissions = $roledata['permision'];

        foreach ($permissions as $permission) {
            RolePermissionModel::create([
                'feature_name' => $permission['feature_name'],
                'role_id' => $role->id,
                'create' => $permission['create'],
                'view' => $permission['view'],
                'modify' => $permission['modify'],
                'delete' => $permission['delete'],
            ]);
        }
    }

        $accountjson = File::get(database_path("seeders/Imports/Accounts.json"));
        $accountdata = json_decode($accountjson, true);

        foreach ($accountdata as $data) {
            AdminModel::create([
                'role_id' => $data['role_id'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'age' => $data['age'],
                'birthdate' => $data['birthdate'],
                'contact' => $data['contact'],
                'address' => $data['address'],
                'office' => $data['office'],
                'division' => $data['division'],
                'service' => $data['service'],
                'group' => $data['group'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
