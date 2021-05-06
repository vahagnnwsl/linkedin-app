<?php

namespace App\Repositories;

use App\Models\User;


class UserRepository extends Repository
{

    const INACTIVE_STATUS = 0;

    const ACTIVE_STATUS = 1;

    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function getActiveUserByEmail(string $email)
    {
        return $this->model()::whereEmail($email)->whereStatus(1)->first();
    }




    /**
     * @param int $user_id
     * @param array $data
     */
    public function syncConversations(int $user_id, array $data): void
    {

        $this->getById($user_id)->conversations()->sync($data);

    }

    /**
     * @param int $user_id
     * @param array $data
     */
    public function syncKeys(int $user_id, array $data): void
    {
        $this->getById($user_id)->keys()->sync($data);
    }


    /**
     * @param int $user_id
     * @param array $data
     */
    public function attachConversations(int $user_id, array $data): void
    {

        $this->getById($user_id)->conversations()->attach($data);
    }


    /**
     * @return mixed
     */
    public function getLinkedinCredentialsFilledUsers()
    {
        return $this->model()::whereNotNull('linkedin_login')->whereNotNull('linkedin_password')->get();
    }

    /**
     * @param $user_id
     * @param $role_id
     */
    public function syncRole($user_id, $role_id)
    {
        $this->getById($user_id)->roles()->sync($role_id);

    }

    /**
     * @param $user_id
     * @param $account_id
     */
    public function syncAccounts($user_id, $account_id)
    {
        $this->getById($user_id)->accounts()->sync($account_id);
    }
}
