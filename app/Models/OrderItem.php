<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasStore;
    protected $table = 'order_items' ;
    protected $guarded = [];
    protected $hidden = [
     'updated_at' ,
     'paid_at'
    ];
    protected $casts = [
       'options' => 'array'
    ] ;


    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function productVariant(){
        return $this->belongsTo(ProductVariant::class);
    }
}
