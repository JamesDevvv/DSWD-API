<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceModel extends Model
{
    use HasFactory;
    protected $table = 'provinces';

    protected $fillable = [
        'code','region_code','name'
    ];
}
