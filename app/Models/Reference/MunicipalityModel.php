<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalityModel extends Model
{
    use HasFactory;
    protected $table = 'municipalties';

    protected $fillable = [
        'code','province_code','name'
    ];
}
