<?php

namespace App\Repositories;


use App\Models\Company;

class CompanyRepository extends Repository
{
    public function model(): string
    {
        return Company::class;
    }

    /**
     * @return mixed
     */
    public function getFiled()
    {
        return $this->model()::whereNotNull('entityUrn')->get();
    }
}
