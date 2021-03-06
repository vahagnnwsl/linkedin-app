<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ConversationRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Conversation::class;
    }

    /**
     * @param string $entityUrn
     * @return mixed
     */
    public function getByEntityUrn(string $entityUrn)
    {
        return $this->model()::where('entityUrn', $entityUrn)->first();
    }


    /**
     * @param int $id
     */
    public function updateLastActivityAt(int $id): void
    {
        $conversation = $this->getById($id);
        $conversation->update(['lastActivityAt' => date('Y-m-d H:m:s')]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateOrCreateCollection(array $data): array
    {

        return collect($data)->map(function ($item) {
            $item = $item->getAttributes();
            return $this->model()::updateOrCreate([
                'entityUrn' => $item['entityUrn']
            ], $item)->id;

        })->toArray();
    }


    /**
     * @param int $account_id
     * @param int $start
     * @param string|null $key
     * @param string|null $distance
     * @return mixed
     */
    public function getByAccountId(int $account_id, int $start = 0, string $key = null, ?string $distance = 'all', ?string $condition = 'all')
    {

        $take = $key ? 400 : 10;
        return $this->model()::where('account_id', $account_id)->whereNotNull('connection_id')
            ->when($key, function ($query) use ($key, $distance) {

                $query->when($distance === 'connections', function ($subQ) use ($key, $distance) {
                    $subQ->whereHas('connection', function ($q) use ($key) {
                        $q->where('connections.firstName', 'LIKE', "%" . $key . "%")
                            ->orWhere('connections.lastName', 'LIKE', "%" . $key . "%")
                            ->orWhere(DB::raw(' CONCAT(connections.firstName," ", connections.lastName)'), 'LIKE', "%" . $key . "%")
                            ->orWhere(DB::raw(' CONCAT(connections.lastName," ", connections.firstName)'), 'LIKE', "%" . $key . "%");
                    });

                });
                $query->when($distance === 'messages', function ($subQ) use ($key, $distance) {
                    $subQ->whereHas('messages', function ($q) use ($key) {
                        $q->where('messages.text', 'LIKE', "%" . $key . "%");
                    });
                });
                $query->when($distance === 'all', function ($subQ) use ($key, $distance) {
                    $subQ->where(function ($q) use ($key) {
                        $q->whereHas('connection', function ($sQ) use ($key) {
                            $sQ->where('connections.firstName', 'LIKE', "%" . $key . "%")
                                ->orWhere('connections.lastName', 'LIKE', "%" . $key . "%")
                                ->orWhere(DB::raw(' CONCAT(connections.firstName," ", connections.lastName)'), 'LIKE', "%" . $key . "%")
                                ->orWhere(DB::raw(' CONCAT(connections.lastName," ", connections.firstName)'), 'LIKE', "%" . $key . "%");
                        });
                    })->orWhere(function ($q) use ($key) {
                        $q->whereHas('messages', function ($sQ) use ($key) {
                            $sQ->where('messages.text', 'LIKE', "%" . $key . "%");
                        });
                    });
                });
            })
            ->when($distance, function ($query) use ($condition) {
                if ($condition === 'not_answered') {
                    $query->whereHas('connection', function ($q) {
                        $q->doesnthave('messages');
                    })->whereHas('messages');
                }elseif ($condition === 'answered'){
                    $query->whereHas('connection', function ($q) {
                        $q->whereHas('messages');
                    });
                }
            })
            ->skip($start)->take($take)->orderByDesc('lastActivityAt')->get();
    }

    /**
     * @param int $id
     * @param int $start
     * @return mixed
     */
    public function getMessages(int $id, int $start = 0)
    {
        $conversation = $this->getById($id);

        return $conversation->messages()->orderByDesc('date')->whereIsDelete(0)->skip($start)->take(10)->get();
    }

    /**
     * @return mixed
     */
    public function getAllMessages(int $id, $re = 'DESC')
    {
        $conversation = $this->getById($id);

        return $conversation->messages()->orderBy('date', $re )->whereIsDelete(0)->get();
    }

    /**
     * @param int $connection_id
     * @param array $accounts_ids
     * @return mixed
     */
    public function getConnectionConversationsByConnectionAndAccount(int $connection_id, array $accounts_ids)
    {
        return $this->model()::where('connection_id', $connection_id)->whereIn('account_id', $accounts_ids)->get();
    }

    /**
     * @param int $connection_id
     * @param int $accounts_id
     * @return mixed
     */
    public function getConnectionConversationByConnectionAndAccount(int $connection_id, int $accounts_id)
    {
        return $this->model()::where('connection_id', $connection_id)->where('account_id', $accounts_id)->first();
    }


    public function getByOffsetLimit(int $offset, int $limit) {
        return $this->model()::offset($offset)->take($limit)->get();
    }
}
