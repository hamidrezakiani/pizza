<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];

    public function users()
    {
       return $this->belongsToMany(User::class,'roles_users','role_id','user_id');
    }



    public function actions()
    {
        return $this->belongsToMany(Action::class, 'actions_roles', 'role_id', 'action_id');
    }
}
