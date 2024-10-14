<?php

namespace App\Models\EOCLGU;

use App\Models\AdminModel;
use App\Models\Core\Media;
use App\Models\Reference\BarangayModel;
use App\Models\Reference\MunicipalityModel;
use App\Models\Reference\ProvinceModel;
use App\Models\User;
use Faker\Provider\sv_SE\Municipality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InfoGraphicModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

}
