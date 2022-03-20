<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Moderator  extends Authenticatable
{
    use SoftDeletes;

    protected $guard = 'moderator';

    protected  $fillable = [
        'email',
        'password',
        'password_non_hash'
    ];
}
