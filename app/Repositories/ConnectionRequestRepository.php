<?php

namespace App\Repositories;


use App\Models\ConnectionRequest;

class ConnectionRequestRepository extends Repository
{
    public static int $PENDING_STATUS = 0;
    public static int $ACCEPTED_STATUS = 1;

    public function model(): string
    {
        return ConnectionRequest::class;
    }


    /**
     * @param int $account_id
     * @param array $ides
     * @return mixed
     */
    public function updateCollectionStatusAndReturnRecordsConnectionIdes(int $account_id, array $ides)
    {
        $this->model()::where('account_id', $account_id)->whereNotIn('id', $ides)->update(['status' => self::$ACCEPTED_STATUS]);

        return $this->model()::where('account_id', $account_id)->where('status', self::$ACCEPTED_STATUS)->pluck('connection_id')->toArray();
    }
}
