<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogModel extends Model
{
    use HasFactory;

    protected $table = 'user_logs';


    protected $fillable = [
       'type',
       'user_id',
       'activity',
    ];


    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'id', 'user_id');
    }

    public function qrt_public()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
