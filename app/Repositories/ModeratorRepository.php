<?php

namespace App\Repositories;


use App\Models\Moderator;

class ModeratorRepository extends Repository
{
    public function model(): string
    {
        return Moderator::class;
    }
}
