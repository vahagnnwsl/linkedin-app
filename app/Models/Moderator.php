<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Moderator  extends Authenticatable
{
    protected $guard = 'moderator';

    protected  $fillable = [
        'email',
        'password',
        'password_non_hash'
    ];

}
