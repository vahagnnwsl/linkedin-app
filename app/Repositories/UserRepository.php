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
     * @param int $model_id
     * @param string $relation_name
     * @param array $relation_ides
     */
    public function syncRelation(int $model_id,string $relation_name, array $relation_ides)
    {
        $this->getById($model_id)->$relation_name()->sync($relation_ides);
    }
}
