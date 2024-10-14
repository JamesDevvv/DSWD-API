<?php

namespace App\Models\EOCLGU;

use App\Models\AdminModel;
use App\Models\Core\Media;
use App\Models\Reference\BarangayModel;
use App\Models\Reference\MunicipalityModel;
use App\Models\Reference\ProvinceModel;
use App\Models\Reference\RoleModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReportArchiveModel extends Model
{
    use HasFactory;


    protected $table = 'report_archives';
    protected $fillable = [
        'user_id',
        'created_by',
        'lgu_id',
        'incident_code',
        'incident_date',
        'disaster_type',
        'start',
        'end',
        'longitude',
        'latitude',
        'district_code',
        'municipality_code',
        'barangay_code',
        'no_families',
        'no_individual',
        'dead',
        'injured',
        'missing',
        'residential',
        'commercial',
        'mix',
        'total_damage',
        'partial_damage',
        'situational_overview',
        'augmentation',
        'remarks',
        'file_id',
        'total_report',
        'progress_status',
        'dromic_status',
        'lce_id',
        'status'
    ];





    public function disaggregated()
    {
        return $this->hasMany(DisaggregatedModel::class,'archived_id','id');
    }


    public function district()
    {
        return $this->hasOne(ProvinceModel::class,'code','district_code');
    }
    public function municipality()
    {
        return $this->hasOne(MunicipalityModel::class,'code','municipality_code');
    }
    public function barangay()
    {
        return $this->hasOne(BarangayModel::class,'code','barangay_code');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function admin()
    {
        return $this->hasOne(AdminModel::class,'id','user_id');
    }
    public function lgu()
    {
        return $this->hasOne(RoleModel::class,'id','lgu_id');
    }

    public function evacuation(){

        return $this->hasMany(EvacuationModel::class,'archived_id','id');
    }
    public function files()
    {
        return $this->hasMany(Media::class,'model_id','file_id');
    }

    public function augmentation_files()
    {
        return $this->hasMany(Media::class,'custom_id','incident_code');
    }
}
