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


class Messages
{

    protected $data;

    const FROM_KEY = '*from';
    const MESSAGE_TYPE = 'com.linkedin.voyager.messaging.Event';

    public static function invoke(object $data, string $conversation_urn, $lastActivate = null): array
    {

        if (!count($data->included)) {
            return [
                'success' => false
            ];
        }

        $data = collect($data->included)->groupBy('$type');

        $messagesData = $data[self::MESSAGE_TYPE];

        $messages = $messagesData->map(function ($item) use ($conversation_urn) {
            return [
                'text' => isset($item->eventContent) && isset($item->eventContent->attributedBody) ? $item->eventContent->attributedBody->text : null,
                'attachments' => isset($item->eventContent) && isset($item->eventContent->attachments) ? $item->eventContent->attachments[0] : null,
                'media' => isset($item->eventContent) && isset($item->eventContent->customContent) && isset($item->eventContent->customContent->media) && isset($item->eventContent->customContent->media->previewgif) ? $item->eventContent->customContent->media->previewgif : null,
                'user_entityUrn' => Helper::searchInString($item->{self::FROM_KEY}, 'urn:li:fs_messagingMember:(' . $conversation_urn . ',', ')'),
                'entityUrn' => Helper::searchInString($item->entityUrn, 'urn:li:fs_event:(' . $conversation_urn . ',', ')'),
                'date' => Carbon::createFromTimestampMsUTC($item->createdAt)->toDateTimeString(),
                'createdAt' => $item->createdAt
            ];
        });

        if ($lastActivate) {
            $messages = $messages->filter(function ($item) use ($lastActivate) {
                return $item['createdAt'] > $lastActivate ;
            });

            if (!count($messages)) {
                return [
                    'success' => false
                ];
            }
        }

        return [
            'success' => true,
            'data' => $messages->toArray(),
            'lastActivityAt' => $messagesData->min('createdAt')
        ];
    }
}

