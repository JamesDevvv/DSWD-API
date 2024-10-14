<?php

namespace App\Models\Chats;

use App\Models\AdminModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatModel extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = ['id','sender_type', 'sender_id', 'receiver_type', 'receiver_id', 'is_seen'];

    public function messages()
    {
        return $this->hasMany(MessageModel::class,'chat_id','id');
    }




    public function admin_sender()
    {
        return $this->hasOne(AdminModel::class, 'id', 'sender_id');
    }

    public function qrt_sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function admin_reciever()
    {
        return $this->hasOne(AdminModel::class, 'id', 'receiver_id');
    }

    public function qrt_reciever()
    {
        return $this->hasOne(User::class, 'id', 'receiver_id');
    }
}
