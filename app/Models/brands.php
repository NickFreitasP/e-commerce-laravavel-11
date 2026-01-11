<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class brands extends Model
{
    protected $fillable =
    [
        "image",
        "name",
    ];

    public function product(){
        return $this->hasMany(Product::class);
    }



}
