<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasStore;
    use HasFactory ;
    protected $guarded = [];
    public function cities()
    {
        return $this->hasMany(ShippingZoneCity::class);

    }

}
