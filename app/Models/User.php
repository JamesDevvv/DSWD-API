<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Core\Media;
use App\Models\User\TrainingModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'id',
        'id_number',
        'role_id',
        'type',
        'team',
        'fullname',
        'age',
        'address',
        'contact',
        'province_code',
        'municipality_code',
        'barangay_code',
        'postal_code',
        'gender',
        'email',
        'provider',
        'provider_id',
        'password',
        'status',
        'approver_id',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->useDisk('avatar');
    }

    public function getMediasAttribute()
    {
        return Media::where('model_id', $this->id)
            ->where('model_type', User::class)
            ->latest()
            ->first();
    }
    public function avatar()
    {
        return $this->hasOne(Media::class,'id','id');
    }
    public function last_login()
    {
        return $this->hasOne(PersonalTokenModel::class, 'tokenable_id','id');
    }
    public function training_files()
    {
        return $this->hasMany(TrainingModel::class,'user_id','id');
    }

}
