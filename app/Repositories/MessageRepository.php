<?php

namespace App\Repositories;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class MessageRepository extends Repository
{


    const DRAFT_STATUS = 0;
    const SENDED_STATUS = 1;

    const NOT_RECEIVE_EVENT = 0;
    const RECEIVE_EVENT = 1;

    /**
     * @return string
     */
    public function model(): string
    {
        return Message::class;
    }


    /**
     * @param array $requestData
     * @param int $conversation_id
     * @param int $user_id
     * @param int $account_id
     * @param string $account_entityUrn
     * @param int $status
     * @param int $event
     * @param bool $is_parser
     */
    public function updateOrCreateCollection(array $requestData, int $conversation_id, int $user_id, int $account_id, string $account_entityUrn, int $status = self::DRAFT_STATUS, int $event = self::NOT_RECEIVE_EVENT, bool $is_parser = false): void
    {
        collect($requestData)->map(function ($item) use ($conversation_id, $user_id, $account_id, $account_entityUrn, $status, $event, $is_parser) {

            if ($account_entityUrn === $item['user_entityUrn']) {
                $item['user_id'] = $user_id;
                $item['account_id'] = $account_id;
            } else {
                $item['connection_id'] = (new ConnectionRepository())->getIdByEntityUrn($item['user_entityUrn'])->id ?? null;

                if (is_null($item['connection_id'])) {
                    return true;
                }
            }

            $item['conversation_id'] = $conversation_id;
            $item['status'] = $status;
            $item['event'] = $event;

            if ($is_parser) {
                $this->model()::unsetEventDispatcher();
            }

            $this->model()::updateOrCreate([
                'entityUrn' => $item['entityUrn']
            ], Arr::except($item, 'user_entityUrn'));


            return true;
        });


    }

    /**
     * @param int $conversation_id
     * @param string $user_entityUrn
     * @return mixed
     */
    public function getConversationMessagesForUser(int $conversation_id, string $user_entityUrn)
    {
        return $this->model()::whereConversationId($conversation_id)->where(function ($q) use ($user_entityUrn) {

            return $q->where('status', self::SENDED_STATUS)->orWhere(['status' => self::DRAFT_STATUS, 'user_entityUrn' => 'ACoAACKvpZ0B_D57F3IJRPfyBnZoFsshG69_rrg']);

        })->orderBy('date')->get();

    }


    /**
     * @param $conversation_id
     * @return mixed
     */
    public function getMessagesByConversationId($conversation_id)
    {

        return $this->model()::whereConversationId($conversation_id)->with('connection')->orderByDesc('date')->paginate(20);
    }

}
