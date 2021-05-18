<?php

namespace App\Repositories;


use App\Models\Country;

class CountryRepository extends Repository
{
    public function model(): string
    {
        return Country::class;
    }


}
