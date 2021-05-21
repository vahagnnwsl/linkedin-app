<?php

namespace App\Repositories;


use App\Models\Key;

class KeyRepository extends Repository
{

    public static $ACTIVE_STATUS = 1;
    public static $INACTIVE_STATUS = 0;

    public function model(): string
    {
        return Key::class;
    }


    /**
     * @return mixed
     */
    public function getActives()
    {
        return $this->model()::whereStatus(self::$ACTIVE_STATUS)->get();
    }

    /**
     * @param int $id
     * @param array $accounts
     */
    public function syncAccounts(int $id, array $accounts): void
    {
        $this->getById($id)->accounts()->sync($accounts);
    }

    /**
     * @param int $id
     * @param array $proxies
     */
    public function syncProxies(int $id, array $proxies): void
    {
        $this->getById($id)->proxies()->sync($proxies);
    }

}
