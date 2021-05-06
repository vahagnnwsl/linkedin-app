<?php

namespace App\Linkedin\DTO;

class  Conversation extends AbstractDTO
{

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        return [
            'entityUrn',
            'unreadCount',
            'data',
            'lastActivityTcp',
            'lastActivityAt'
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [

        ];
    }
}
