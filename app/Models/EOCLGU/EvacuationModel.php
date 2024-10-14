<?php

namespace App\Models\EOCLGU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationModel extends Model
{
    use HasFactory;
    protected $table = 'evacuations';
    protected $fillable = [
        'id',
        'archived_id',
        'incident_code',
        'name'
    ];

    public function inside()
    {
        return $this->hasOne(InsideModel::class, 'evacuation_id', 'id');
    }
    public function outside()
    {
        return $this->hasOne(OutsideModel::class, 'evacuation_id', 'id');
    }

}
