<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proxy extends Model
{
    use HasFactory,SoftDeletes;
    /**
     * @var string[]
     */
    protected  $fillable = [
        'login',
        'password',
        'ip',
        'port',
        'country',
        'type'
    ];
}
