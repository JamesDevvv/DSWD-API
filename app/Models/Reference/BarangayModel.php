<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayModel extends Model
{
    use HasFactory;

    protected $table = 'barangays';

    protected $fillable = [
        'code','municipality_code','name'
    ];
}
