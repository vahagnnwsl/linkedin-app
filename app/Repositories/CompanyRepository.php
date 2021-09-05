<?php

namespace App\Repositories;


use App\Models\Company;
use Illuminate\Support\Facades\Log;

class CompanyRepository extends Repository
{
    public static $NO_PARSED_STATUS = 0;
    public static $PARSED_SUCCESS_STATUS = 1;
    public static $PARSED_FAILED_STATUS = 2;

    public function model(): string
    {
        return Company::class;
    }

    /**
     * @return mixed
     */
    public function getParsed()
    {
        return $this->model()::where('is_parsed', self::$PARSED_SUCCESS_STATUS)->get();
    }

    /**
     * @return mixed
     */
    public function getNotFiled()
    {
        return $this->model()::whereNull('entityUrn')->get();
    }


    /**
     * @return mixed
     */
    public function getNoParsed()
    {
        return $this->model()::where('is_parsed', self::$NO_PARSED_STATUS)->get();
    }


    /**
     * @param $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->model()::where('name', $name)->first();
    }

}
