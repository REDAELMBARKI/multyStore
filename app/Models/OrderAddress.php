<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasStore;
    use HasFactory ;
    protected $hidden = [
    "id" ,
    "order_id" ,
    "type" ,
    "created_at" ,
    "updated_at" ,
    ];
    protected $guarded = [];
    public function order(){
        return $this->belongsTo(Order::class) ;
    }
}
