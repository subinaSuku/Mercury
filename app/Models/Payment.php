<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payments';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'icon',
        'image',
        'is_active',
        'city_id',
        'is_deleted'
    ];

    // protected $casts = [
    //     'data' => 'json',
    // ];


    public function ServiceAddons()
    {
        return $this->hasMany(ServiceAddon::class, 'addon_id');
    }

    public function ServiceAddon()
    {
        return $this->hasOne(ServiceAddon::class, 'addon_id');
    }

}
