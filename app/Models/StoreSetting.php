<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory, HasStore;
    protected $table = "store_settings";
    protected $guarded  = [];
}
