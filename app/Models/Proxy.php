<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;
    /**
     * @var string[]
     */
    protected $fillable = [
        'login',
        'password',
        'ip',
        'port',
        'country',
        'type'
    ];
}
