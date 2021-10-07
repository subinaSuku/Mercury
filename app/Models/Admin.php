<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email' ,
        'password' ,
        'is_active',
        'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["fullname"];

    public function getFullNameAttribute()
    {
        return ucfirst(strtolower($this->first_name)) .' ' . ucfirst(strtolower($this->last_name));
    }

    // public function getAvatarAttribute()
    // {
    //     $file = ProfileImage::where('model_type' , get_class($this))->where("model_id", $this->id)->where("status" , true)->first();
    //     return isset($file) ? url(Storage::url($file->path)) : "img/user.png";
    // }
}
