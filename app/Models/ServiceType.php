<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{

    protected $table = 'service_types';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'is_expaire',
        'is_active',
        'is_deleted'
    ];

    // protected $casts = [
    //     'data' => 'json',
    // ];

}
