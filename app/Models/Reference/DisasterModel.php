<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisasterModel extends Model
{
    use HasFactory;
    protected $table = 'disaster_types';
    protected $fillable = ['name'];
}
