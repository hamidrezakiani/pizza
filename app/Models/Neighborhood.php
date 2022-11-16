<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighborhood extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'city_id','name','active',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
