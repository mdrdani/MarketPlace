<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    //
    protected $fillable = [
        'photos' , 'products_id'
    ];

    protected $hidden =  [

    ];

    public function products()
    {
        return $this->belongsTo(Product::class,'products_id','id');
    }
}
