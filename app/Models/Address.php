<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
       'user_id',
       'neighborhood_id',
       'address',
       'longitude',
       'latitude',
       'phone',
       'isDefault',
    ];

    public function getCityNameAttribute()
    {
        return $this->neighborhood->city->name;
    }

    public function getProvinceNameAttribute()
    {
        return $this->neighborhood->city->province->name;
    }

    public function getNeighborhoodNameAttribute()
    {
        return $this->neighborhood->name;
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
