<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasStore;
    protected $fillable = ['name', 'claims'];

    protected $casts = [
        'claims' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
