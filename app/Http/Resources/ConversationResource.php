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
            'connection' => $this->connection,
            'account' => $this->account,
            'lastActivityAt' => $this->lastActivityAt,
            'lastActivityAt_diff' => $this->lastActivityAt->diffForHumans(),
        ];
    }
}
