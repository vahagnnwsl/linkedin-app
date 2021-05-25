<?php

namespace App\Repositories;

use App\Models\Conversation;
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
     * @return mixed
     */
    public function getByAccountId(int $account_id, int $start = 0, string $key = null)
    {

        return $this->model()::where('account_id', $account_id)->whereNotNull('connection_id')->when($key, function ($query) use ($key) {
            return $query->whereHas('connection', function ($q) use ($key) {
                return $q->where('connections.firstName', 'LIKE', "%$key%")->orWhere('connections.lastName', 'LIKE', "%$key%");
            });
        })->skip($start)->take(10)->orderByDesc('lastActivityAt')->get();
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
     * @param int $connection_id
     * @param array $accounts_ids
     * @return mixed
     */
    public function getConnectionConversationsByConnectionAndAccount(int $connection_id, array $accounts_ids)
    {

        return $this->model()::where('connection_id', $connection_id)->whereIn('account_id', $accounts_ids)->get();
    }

}
