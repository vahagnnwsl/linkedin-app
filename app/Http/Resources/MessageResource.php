<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'user_id' => $this->user_id,
            'conversation_id' => $this->conversation_id,
            'account_id' => $this->account_id,
            'entityUrn' => $this->entityUrn,
            'connection' => $this->connection,
            'text' => $this->text,
            'date_diff' => $this->date->diffForHumans(),
            'date' => $this->date,
            'status' => $this->status,
            'event' => $this->event,
            'attachments' => $this->attachments,
            'media' => $this->media,
        ];
    }
}
