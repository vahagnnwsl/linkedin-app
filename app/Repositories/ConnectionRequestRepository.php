<?php

namespace App\Repositories;


use App\Models\ConnectionRequest;

class ConnectionRequestRepository extends Repository
{
    public function model(): string
    {
        return ConnectionRequest::class;
    }

}
