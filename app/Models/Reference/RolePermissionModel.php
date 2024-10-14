<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissionModel extends Model
{
    use HasFactory;

    protected $table = "role_permissions";
    protected $fillable = [
       'feature_name',
       'role_id',
       'create',
       'view',
       'modify',
       'delete',
    ];

    public function role()
    {
        return $this->belongsToMany(RoleModel::class,'id','role_id');
    }
}
