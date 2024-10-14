<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'local_goverment_unit',
        'emergency_operation_center',
        'regional_director',
        'local_chief_executive',
        'status',
    ];

    public function permissions(){
        return $this->hasMany(RolePermissionModel::class,'role_id','id');
    }
}
