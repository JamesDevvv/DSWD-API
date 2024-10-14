<?php

namespace App\Models\User;

use App\Models\Core\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class TrainingModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'trainings';
   protected $fillable = [
    'user_id',
    'name',
    'title',
    'type',
    'date',
    'duration',
    'location',
    'conduct_by',
   ];

   public function registerMediaCollections(): void
   {
       $this->addMediaCollection('trainings')->useDisk('trainings');
   }

   public function getMediasAttribute()
   {
       return Media::where('model_id', $this->id)
           ->where('model_type', TrainingModel::class)
           ->latest()
           ->first();
   }

   public function files()
    {
        return $this->hasMany(Media::class,'model_id','id');
    }
}
