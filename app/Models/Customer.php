<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
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
        'std_code',
        'mobile' ,
        'email',
        'password',
        'user_id',
        'customer_no',
        'country_code',
        'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["fullname", "phone", 'customer_number'];

    public function getFullNameAttribute()
    {
        return ucfirst(strtolower($this->first_name)) .' ' . ucfirst(strtolower($this->last_name));
    }

    public function getCustomerNumberAttribute(){
        return "MIC" . $this->customer_no;
    }
 

    public function getPhoneAttribute()
    {
        return "+" .$this->std_code . $this->mobile;
    }

    public function Services()
    {
        return $this->hasMany(CustomerService::class, 'customer_id')->where('is_deleted' , 0);
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
