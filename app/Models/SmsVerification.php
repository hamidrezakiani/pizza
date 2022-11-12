<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsVerification extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'mobile',
        'code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
