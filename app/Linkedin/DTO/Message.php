<?php
namespace App\Linkedin\DTO;

class  Message extends AbstractDTO {

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        return [
            'conversation_id ',
            'conversation_entityUrn',
            'user_entityUrn',
            'text',
            'entityUrn',
            'date'
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
