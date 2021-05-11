<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
     */
    public function update(int $id, array $data)
    {
        $this->model()::whereId($id)->update($data);
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
     * @return mixed
     */
    public function query()
    {
        return $this->model()::querey();
    }


    /**
     * @param array $requestData
     * @param array $userKeysIdes
     * @param string $orderBy
     * @param string $direction
     * @return mixed
     */
    public function filter(array $requestData, array $userKeysIdes = [], string $orderBy = 'created_at', string $direction = 'desc')
    {

        return $this->model()::when(isset($requestData['key']), function ($q) use ($requestData) {
            if ($q->getModel()->getTable() === 'connections') {
                return $q
                    ->where('firstName', 'LIKE', "%" . $requestData['key'] . "%")
                    ->orWhere('lastName', 'LIKE', "%" . $requestData['key'] . "%")
                    ->orWhere('occupation', 'LIKE', "%" . $requestData['key'] . "%");
            }

            return $q->where('name', 'LIKE', "%" . $requestData['key'] . "%");
        })
            ->when(isset($requestData['accounts_ids']) && count($requestData['accounts_ids']), function ($q) use ($requestData) {
                return $q->whereHas('accounts', function ($subQuery) use ($requestData) {
                    return $subQuery->whereIn('accounts.id', $requestData['accounts_ids']);
                });
            })
            ->when(isset($requestData['keys_ids']) && count($requestData['keys_ids']), function ($q) use ($requestData) {
                return $q->whereHas('keys', function ($subQuery) use ($requestData) {
                    return $subQuery->whereIn('keys.id', $requestData['keys_ids']);
                });
            })->when(count($userKeysIdes), function ($q) use ($userKeysIdes) {
//                return $q->whereHas('keys', function ($subQuery) use ($userKeysIdes) {
//                    return $subQuery->whereIn('keys.id', $userKeysIdes);
//                });
            })
            ->orderby($orderBy, $direction)->paginate(20);
    }

    /**
     * @param $joinedFields
     * @param array $enableKeysIdes
     * @return mixed
     */
    public function selectForSelect2($joinedFields, array $enableKeysIdes = [])
    {
        return $this->model()::select(DB::raw("CONCAT(" . $joinedFields . ") AS text"), 'id')->when(count($enableKeysIdes), function ($q) use ($enableKeysIdes) {
            return $q->whereIn('id', $enableKeysIdes);
        })->get()->toArray();
    }

}
