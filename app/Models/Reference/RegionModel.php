<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionModel extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'code','name'
    ];
}
