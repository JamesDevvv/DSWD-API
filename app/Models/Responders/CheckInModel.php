<?php

namespace App\Models\Responders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInModel extends Model
{
    use HasFactory;

    protected $table = 'check_ins';

    protected $fillable =  [
        'user_id','role_id'
    ];


    public function UserDetails()
    {
         return $this->hasOne(User::class,'id','user_id');
    }
}
