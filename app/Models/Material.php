<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'unit',
        'price',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,'materials_products','material_id','product_id');
    }
}
