<?php

namespace App\Models\EOCLGU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InsideModel extends Model
{
    use HasFactory;
    protected $table = 'inside';
    protected $fillable = [
        'evacuation_id',
        'inside_families',
        'inside_individuals',
    ];

}
