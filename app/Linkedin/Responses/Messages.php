<?php

namespace App\Linkedin\Responses;

use App\Linkedin\DTO\Message;
use App\Linkedin\Helper;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class Messages
{
    const TYPE_KEY = '$type';
    const FROM_KEY = '*from';

    const MESSAGE_TYPE = 'com.linkedin.voyager.messaging.Event';

    /**
     * @var object
     */
    protected object $data;

    /**
     * @var Collection
     */
    protected Collection $elements;

    /**
     * @var string
     */
    protected string $conversation_urn;

    /**
     * Messages constructor.
     * @param array $data
     * @param string $conversation_urn
     */
    public function __construct(array $data, string $conversation_urn)
    {
        $this->data = collect($data['data']->included);

        $this->conversation_urn = $conversation_urn;
    }

    /**
     * @return array
     */
    public function initializ(): array
    {

    }
}
