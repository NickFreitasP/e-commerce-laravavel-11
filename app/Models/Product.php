<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function category(){
        return $this->belongsTo(Categorie::class,"category_id");
    }

    public function brand(){

        return $this->belongsTo(brands::class,"brand_id");
    }

    public function imagesGallery(){
        return $this->hasMany(ProductImage::class);
    }


}
