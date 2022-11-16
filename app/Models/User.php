<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->api_token = Str::random(80);
        });
    }

    protected $fillable = [
        'firstName',
        'lastName',
        'mobile',
        'password',
        'api_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function smsVerifications()
    {
        return $this->hasMany(SmsVerification::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_users', 'user_id', 'role_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getIsAdminAttribute()
    {
        return ($this->mobile == '09369422072')||($this->mobile == '09304774819');
    }
}
