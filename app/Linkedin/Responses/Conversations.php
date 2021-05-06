<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;
use App\Linkedin\DTO\Conversation as ConversationDTO;
use App\Linkedin\Helper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Conversations
{

    const TYPE_CONVERSATION = 'com.linkedin.voyager.messaging.Conversation';
    const TYPE_MESSAGING_MEMBER = 'com.linkedin.voyager.messaging.MessagingMember';
    const TYPE_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';

    const PARTICIPANTS_KEY = '*participants';

    const MESSAGING_MEMBER = 'com.linkedin.voyager.messaging.MessagingMember';
    const MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';

    /**
     * @var array
     */
    protected  $data;

    /**
     * @var string
     */
    protected string $user_entityUrn;
    /**
     * @var Collection
     */
    protected Collection $elements;

    /**
     * Conversations constructor.
     * @param array $data
     * @param string $user_entityUrn
     */
    public function __construct(array $data, string $user_entityUrn)
    {
        $this->user_entityUrn = $user_entityUrn;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function initializ(): array
    {


    }
}
