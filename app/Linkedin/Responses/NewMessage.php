<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;
use App\Linkedin\DTO\AbstractDTO;
use App\Linkedin\DTO\Message;
use App\Linkedin\DTO\Profile;
use App\Linkedin\Helper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;


class NewMessage
{

    protected $data;
    protected $login;

    const TYPE_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';
    const TYPE_KEY = '$type';
    const MESSAGE_TYPE = 'com.linkedin.voyager.messaging.Event';

    public function __construct(array $data, string $login)
    {
        $this->data = $data;
        $this->login = $login;
    }


    public function __invoke(): array
    {
        $data = collect($this->data);

        $profile = $data->first(function ($item) {

            return $item[self::TYPE_KEY] === self::TYPE_MINI_PROFILE;
        });

        $writer = [
            'firstName' => $profile['firstName'] ?? '',
            'lastName' => $profile['lastName'] ?? '',
            'occupation' => $profile['occupation'] ?? '',
            'publicIdentifier' => $profile['publicIdentifier'] ?? '',
            'entityUrn' => explode(':', $profile['entityUrn'])[3],
        ];

        if (isset($profile['picture']) && isset($profile['picture']['artifacts']) && count($profile['picture']['artifacts']) > 1) {
            $writer['picture'] = $profile['picture']['rootUrl'] . $profile['picture']['artifacts'][1]['fileIdentifyingUrlPathSegment'];
        }

        $event = $data->first(function ($item) {
            return $item[self::TYPE_KEY] === self::MESSAGE_TYPE;
        });

        $conversation = [
            'entityUrn' => Helper::searchInString($event['entityUrn'], 'urn:li:fs_event:(', ','),
            'lastActivityAt' => Carbon::createFromTimestampMsUTC($event['createdAt'])->toDateTimeString(),
        ];

        $message['entityUrn'] = explode(':', $event['backendUrn'])[3];
        $message['date'] = Carbon::createFromTimestampMsUTC($event['createdAt'])->toDateTimeString();

        if (isset($event['eventContent']['attributedBody']['text'])) {
            $message['text'] = $event['eventContent']['attributedBody']['text'];
        }

        if (isset($event['eventContent']['customContent']) && isset($event['eventContent']['customContent']['media'])) {
            $message['media'] = $event['eventContent']['customContent']['media']['previewgif'];
        }

        if (isset($event['eventContent']['attachments'])) {
            $message['attachments'] = $event['eventContent']['attachments'][0];
        }

        return [
            'login' => $this->login,
            'message' => $message,
            'writer' => $writer,
            'conversation' => $conversation
        ];
    }
}

