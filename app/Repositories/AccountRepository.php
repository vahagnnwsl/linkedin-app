<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Support\Facades\DB;


class AccountRepository extends Repository
{

    public static $ACTIVE_STATUS = 1;
    public static $INACTIVE_STATUS = 0;


    public static $TYPE_REAL = 1;
    public static $TYPE_UNREAL = 2;


    public function model(): string
    {
        return Account::class;
    }

    /**
     * @param string $login
     * @return mixed
     */
    public function getByLogin(string $login)
    {
        return $this->model()::whereLogin($login)->first();
    }

    /**
     * @param string $entityUrn
     * @return mixed
     */
    public function checkAccountExist(string $entityUrn)
    {
        return $this->model()::where('entityUrn', $entityUrn)->exists();
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function syncConnections(int $id, array $data): void
    {
        $this->model()::whereId($id)->connections()->sync($data);
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function attachConnections(int $id, array $data): void
    {
        DB::table('account_connections')->insert(['account_id' => $id, 'connection_id' => $data[0]]);
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function getConversations(int $id)
    {
        return $this->getById($id)->conversations()->paginate(20);
    }

    /**
     * @param int $id
     * @param int $conversation_id
     * @return bool
     */
    public function checkConversationRelationExist(int $id, int $conversation_id): bool
    {
        if ($this->getById($id)->conversations()->whereId($conversation_id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * @param int $id
     * @param int $connection_id
     * @return bool
     */
    public function checkConnectionRelationExist(int $id, int $connection_id): bool
    {
        if ($this->getById($id)->connections()->where('connections.id',$connection_id)->exists()) {
            return true;
        }

        return false;
    }


    /**
     * @param int $id
     * @param array $proxies
     */
    public function syncProxies(int $id, array $proxies): void
    {
        $this->getById($id)->proxies()->sync($proxies);
    }


    /**
     * @return mixed
     */
    public function getRandomFirst()
    {
        return $this->model()::where('status', self::$ACTIVE_STATUS)->inRandomOrder()->first();
    }


    /**
     * @return mixed
     */
    public function getAllRealAccounts()
    {
        return $this->model()::where('type', self::$TYPE_REAL)->get();
    }

    /**
     * @return mixed
     */
    public function getAllUnRealAccounts()
    {
        return $this->model()::where('type', self::$TYPE_UNREAL)->get();
    }

    /**
     * @param int $type
     * @return mixed
     */
    public function getByType(int $type)
    {
        return $this->model()::where('type', $type)->get();
    }

    /**
     * @param $account_id
     * @param $connection_id
     */
    public function attachConnection($account_id,$connection_id){
        DB::table('account_connections')
            ->updateOrInsert(
                ['account_id' => $account_id, 'connection_id' => $connection_id],
                ['account_id' => $account_id, 'connection_id' => $connection_id]
            );
    }
}
