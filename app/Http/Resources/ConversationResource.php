<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'entityUrn' => $this->entityUrn,
            'connection' => [
                'id'=>$this->connection->id,
                'entityUrn'=>$this->connection->entityUrn,
                'fullName'=>$this->connection->fullName,
                'occupation'=>$this->connection->occupation,
                'image'=>$this->connection->photo,
                'conversations'=>$this->connection->getConversations()
            ],
            'account' => $this->account,
            'lastActivityAt' => $this->lastActivityAt,
            'lastMessage' => $this->messages()->orderBy('date', 'desc')->first() ? $this->messages()->orderBy('date', 'desc')->first()->text : '',
            'lastActivityAt_diff' => $this->lastActivityAt->diffForHumans(),
        ];
    }
}
