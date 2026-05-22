<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Model;

class GoogleSheet extends Model
{
    use HasStore;
   
    // allow mass assignment
    protected $guarded = [];
}
