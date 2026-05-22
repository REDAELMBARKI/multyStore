<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasStore;
    use HasFactory ;
    public function products(){
         $this->belongsToMany(Product::class ) ;
    }
}
