<?php

namespace App\Repositories;


use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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


    /**
     * @param string $name
     * @return Collection
     */
    public function searchByName(string $name):Collection
    {
        return $this->model()::select('id',DB::raw('name as text'))->where('name', 'LIKE','%'.$name.'%')->whereNotNull('entityUrn')->where('is_parsed',self::$PARSED_SUCCESS_STATUS)->get();
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getByIds(array $ids):Collection
    {
        return $this->model()::select('id',DB::raw('name as text'))->whereIn('id', $ids)->where('is_parsed',self::$PARSED_SUCCESS_STATUS)->get();
    }
}
