<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasStore;
    use HasFactory ; 
    public function products(){
        return $this->belongsToMany(Product::class , 'attribute_product');
    }
}
