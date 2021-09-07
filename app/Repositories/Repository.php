<?php


namespace App\Repositories;

use App\Models\Status;
use Illuminate\Support\Facades\DB;

abstract class Repository
{

    /**
     * @return mixed
     */
    abstract function model();


    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model()::whereId($id)->first();
    }


    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model()::get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model()::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->model()::whereId($id)->update($data);
    }

    /**
     * @param array $requestData
     * @param array $connArray
     * @return mixed
     */
    public function updateOrCreate(array $connArray, array $requestData)
    {
        return $this->model()::updateOrCreate($connArray, $requestData);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function paginate(int $limit = 20)
    {
        return $this->model()::paginate($limit);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->model()::destroy($id);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return $this->model()::querey();
    }


    /**
     * @param $joinedFields
     * @param array $enableKeysIdes
     * @return mixed
     */
    public function selectForSelect2($joinedFields, array $query = [])
    {
        return $this->model()::select(DB::raw("CONCAT(" . $joinedFields . ") AS text"), 'id')->when(count($query), function ($q) use ($query) {
            return $q->where($query);
        })->get()->toArray();
    }

}
