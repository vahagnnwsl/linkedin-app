<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{

    /**
     * @var string
     */
    public $collects = 'App\Http\Resources\UserResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

    }
}
