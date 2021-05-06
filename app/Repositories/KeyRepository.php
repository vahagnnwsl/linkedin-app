<?php

namespace App\Repositories;


use App\Models\Key;

class KeyRepository extends Repository
{
    public function model(): string
    {
        return Key::class;
    }


}
