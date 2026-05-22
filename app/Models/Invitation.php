<?php

namespace App\Models;

use App\Traits\HasStore;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasStore;
    protected $fillable = [
        'email',
        'role_id',
        'token',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
