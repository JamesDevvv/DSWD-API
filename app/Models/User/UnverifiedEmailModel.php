<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnverifiedEmailModel extends Model
{
    use HasFactory;


    protected $table = 'unverified_emails';
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'validated_at'
    ];
}
