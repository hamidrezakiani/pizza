<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use HasFactory,SoftDeletes;

    public function roles()
    {
        return $this->belongsToMany(Role::class,'actions_roles','action_id','role_id');
    }
}
