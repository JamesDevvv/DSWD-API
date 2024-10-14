<?php

namespace App\Models;

use App\Models\Reference\RoleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdminModel extends Model implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table="admins";

    protected $fillable = [
        'id',
        'role_id',
        'sub_role_id',
        'firstname',
        'lastname',
        'age',
        'birthdate',
        'contact',
        'address',
        'office',
        'division',
        'service',
        'group',
        'email',
        'status',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->hasOne(RoleModel::class,'id','role_id');
    }

    public function sub_role()
    {
        return $this->hasOne(RoleModel::class,'id','sub_role_id');
    }

    public function fullname()
    {
         $fullname = $this->firstname . ' ' . $this->lastname;
        return $fullname;
    }

    public function last_login()
    {
        return $this->hasOne(PersonalTokenModel::class, 'tokenable_id','id');
    }

}
