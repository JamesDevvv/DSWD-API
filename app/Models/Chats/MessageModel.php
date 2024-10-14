<?php

namespace App\Models\Chats;

use App\Models\AdminModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = ['id','chat_id', 'group_id', 'sender_type', 'sender_id', 'content'];


    public function admin_sender()
    {
        return $this->hasOne(AdminModel::class, 'id', 'sender_id');
    }

    public function qrt_sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

}
