<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * @var string[]
     */
    protected  $fillable = [
        'name',
        'entityUrn'
    ];

}
