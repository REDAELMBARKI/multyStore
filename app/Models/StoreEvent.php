<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreEvent extends Model
{
    use HasStore;
    /** @use HasFactory<\Database\Factories\StoreEventFactory> */
    use HasFactory;
}
