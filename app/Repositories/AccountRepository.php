<?php

namespace App\Repositories;

use App\Models\Account;


class AccountRepository extends Repository
{

    public static $ACTIVE_STATUS = 1;
    public static $INACTIVE_STATUS = 0;

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
        $this->model()::whereId($id)->connections()->attach($data);
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
        if ($this->getById($id)->connections()->whereId($connection_id)->exists()) {
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
        return $this->model()::where('status',self::$ACTIVE_STATUS)->inRandomOrder()->first();

    }
}
