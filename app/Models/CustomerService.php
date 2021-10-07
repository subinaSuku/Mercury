<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    protected $table = 'customer_services';
    public $timestamps = true;

    protected $fillable = [
        'service_type_id',
        'customer_id',
        'account_name',
        'bill_number',
        'expires_at',
        'data',
        'is_active',
        'user_id',
        'is_deleted'
    ];

    protected $casts = [
        'data' => 'json',
    ];

    protected $appends = ["is_expired", "expired_at"];

    public function getIsExpiredAttribute()
    {
        return $this->expires_at < Carbon::now()  ? 1 : 0;
    }

    public function getExpiredAtAttribute()
    {
        return Carbon::parse($this->expires_at)->format('d-m-Y');
    }

    public function Type()
    {
        return $this->belongsTo(ServiceType::class , 'service_type_id')->where('is_deleted' , 0);
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
