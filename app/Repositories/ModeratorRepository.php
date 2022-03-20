<?php

namespace App\Repositories;


use App\Models\Moderator;

class ModeratorRepository extends Repository
{
    public function model(): string
    {
        return Moderator::class;
    }

    /**
     * @return mixed
     */
    public function getCount(){
        return $this->model()::count();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model()::whereId($id)->first();
    }

}
