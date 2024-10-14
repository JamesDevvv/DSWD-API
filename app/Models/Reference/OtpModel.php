<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpModel extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'email',
        'otp',
        'expires_at'
    ] ;
}
