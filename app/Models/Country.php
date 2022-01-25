<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    /**
     * @var string[]
     */
    protected  $fillable = [
        'name',
        'entityUrn'
    ];

}
