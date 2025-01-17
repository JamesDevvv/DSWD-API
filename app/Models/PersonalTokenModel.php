<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTokenModel extends Model
{
    use HasFactory;

    protected $table = 'personal_access_tokens';

    protected $fillable = [
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at'
    ];
}
