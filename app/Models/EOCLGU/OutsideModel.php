<?php

namespace App\Models\EOCLGU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OutsideModel extends Model
{
    use HasFactory;

    protected $table = 'outside';
    protected $fillable = [
        'evacuation_id',
        'outside_families',
        'outside_individuals',
    ];
}
