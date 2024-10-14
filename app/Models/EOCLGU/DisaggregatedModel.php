<?php

namespace App\Models\EOCLGU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisaggregatedModel extends Model
{
    use HasFactory;
    protected $table = 'disaggregated';
    protected $fillable = [
        'info_graphics_id',
        'archived_id',
        'age',
        'male',
        'female',
    ];
}
