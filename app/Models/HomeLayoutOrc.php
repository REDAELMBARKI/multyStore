<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Model;

class HomeLayoutOrc extends Model
{
    use HasStore;
    protected $fillable = ['sortable_id', 'sortable_type', 'order'];

    public function sortable()
    {
        return $this->morphTo();
    }
}
