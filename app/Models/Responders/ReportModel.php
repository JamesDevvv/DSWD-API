<?php

namespace App\Models\Responders;

use App\Models\AdminModel;
use App\Models\Core\Media;
use App\Models\EOCLGU\DisaggregatedModel;
use App\Models\EOCLGU\EvacuationModel;
use App\Models\EOCLGU\InfoGraphicModel;
use App\Models\Reference\BarangayModel;
use App\Models\Reference\MunicipalityModel;
use App\Models\Reference\ProvinceModel;
use App\Models\Reference\RegionModel;
use App\Models\Reference\RoleModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class ReportModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = "reports";
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
        'total_report',
        'progress_status',
        'status',
        'dromic_status',
        'lce_id',
        'validated_at',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reports')->useDisk('reports');
    }

    public function getMediasAttribute()
    {
        return Media::where('model_id', $this->id)
            ->where('model_type', ReportModel::class)
            ->latest()
            ->first();
    }
    public function disaggregated()
    {
        return $this->hasMany(DisaggregatedModel::class,'info_graphics_id','id');
    }
    public function files()
    {
        return $this->hasMany(Media::class,'model_id','id');
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

        return $this->hasMany(EvacuationModel::class,'incident_code','incident_code');
    }
    public function augmentation_files()
    {
        return $this->hasMany(Media::class,'custom_id','incident_code');
    }
}
