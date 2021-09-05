<?php

namespace App\Repositories;
use App\Models\Skill;

class SkillRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Skill::class;
    }


}
