<?php

namespace App\Repositories;


use App\Models\Proxy;

class ProxyRepository extends Repository
{
    public function model(): string
    {
        return Proxy::class;
    }

    /**
     * @return mixed
     */
    public function inRandomOrderFirst()
    {
        return $this->model()::inRandomOrder()->first();
    }

}
