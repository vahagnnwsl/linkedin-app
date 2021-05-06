<?php
namespace App\Linkedin\DTO;

class  Profile extends AbstractDTO {

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        return [
            'firstName',
            'lastName',
            'entityUrn',
            'publicIdentifier',
            'occupation',
            'secondaryTitle',
            'image'
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
