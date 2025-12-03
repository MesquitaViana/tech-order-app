<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        // permite passar senha “crua” que ele já faz o hash
        $this->attributes['password'] = Hash::make($value);
    }
}
