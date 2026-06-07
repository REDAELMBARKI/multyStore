<?php

namespace App\Repositories;

use App\Models\ShippingZoneCity;
use Illuminate\Support\Collection;

class ShippingRepository 
{
    public function getCity(string $city){
        return ShippingZoneCity::where("city" , $city)->first();
    }

}