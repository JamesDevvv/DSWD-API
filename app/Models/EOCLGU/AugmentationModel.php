<?php

namespace App\Models\EOCLGU;

use App\Models\Core\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class AugmentationModel extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $table = 'augmentations';

    protected $fillable = [
       'id',
       'incident_code',
       'lgu_id',
       'remarks',
       'eoc_approver',
       'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('augmentation')->useDisk('augmentation');
    }

    public function getMediasAttribute()
    {
        return Media::where('custom_id', $this->incident_code)
            ->where('model_type', AugmentationModel::class)
            ->latest()
            ->first();
    }

   public function files()
    {
        return $this->hasMany(Media::class,'custom_id','incident_code');
    }

}
