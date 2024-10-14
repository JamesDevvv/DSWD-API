<?php

namespace App\Models\Reference;

use App\Models\AdminModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [

        'type',
        'sender_type',
        'from_id',
        'receiver_type',
        'recieve_id',
        'group_recieve_id',
        'message_id',
        'is_read',
        'content',
    ];

    public function from_admin()
    {
        return $this->hasOne(AdminModel::class,'id','from_id');
    }
    public function from_qrt()
    {
        return $this->hasOne(User::class,'id','from_id');
    }

    public function group()
    {
        return $this->hasOne(RoleModel::class,'id','group_recieve_id');
    }
}
