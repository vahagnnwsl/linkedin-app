<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;
use App\Linkedin\DTO\Conversation as ConversationDTO;
use App\Linkedin\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class Response
{

    const TYPE_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';
    const TYPE_CONVERSATION = 'com.linkedin.voyager.messaging.Conversation';
    const TYPE_MESSAGING_MEMBER = 'com.linkedin.voyager.messaging.MessagingMember';
    const PARTICIPANTS_KEY = '*participants';
    const MESSAGING_MEMBER = 'com.linkedin.voyager.messaging.MessagingMember';
    const IDENTITY_PROFILE_KEY = 'com.linkedin.voyager.dash.identity.profile.Profile';
    const TYPE_KEY = '$type';
    const FROM_KEY = '*from';
    const MESSAGE_TYPE = 'com.linkedin.voyager.messaging.Event';

    /**
     * @param array $data
     * @param string $user_entityUrn
     * @return array
     */
    public static function conversations(array $data, string $user_entityUrn): array
    {

        if (!$data['success'] || !isset($data['data']) || !count($data['data']->included)) {
            return [
                'success' => false
            ];
        }

        $data = collect($data['data']->included);

        $data = $data->groupBy('$type');

        $conversations = $data[self::TYPE_CONVERSATION]->filter(function ($conversation) {
            return !$conversation->groupChat;
        });

        $profiles = $data[self::TYPE_MINI_PROFILE]->map(function ($profile) {

            if (isset($profile->picture) && isset($profile->picture->artifacts) && count($profile->picture->artifacts) > 1) {
                $profile->picture = $profile->picture->rootUrl . $profile->picture->artifacts[1]->fileIdentifyingUrlPathSegment;
            }

            $profile->entityUrn = explode(':', $profile->entityUrn)[3];
            $profile->picture = $profile->picture ?? Constants::DEFAULT_AVATAR;

            return collect($profile)->only('firstName', 'lastName', 'picture', 'entityUrn', 'publicIdentifier')->toArray();

        });

        $profiles = $profiles->filter(function ($item) {
            return $item['entityUrn'] !== 'UNKNOWN';
        });

        $conversations = $conversations->map(function ($conversation) use ($profiles, $user_entityUrn) {

            $entity_urn = explode(':', $conversation->entityUrn)[3];


            $participant_urn = Helper::searchInString($conversation->{self::PARTICIPANTS_KEY}[0], 'urn:li:fs_messagingMember:(' . $entity_urn . ',', ')');

            $conversation->data = $profiles->first(function ($profile) use ($participant_urn) {
                return $profile['entityUrn'] === $participant_urn;
            });

            $conversation->entityUrn = $entity_urn;

            $conversation->lastActivityTcp = $conversation->lastActivityAt;

            $conversation->lastActivityAt = Carbon::createFromTimestampMsUTC($conversation->lastActivityAt)->toDateTimeString();


            return new ConversationDTO((array)$conversation);

        });

        $conversations = $conversations->filter(function ($conversation) {
            return $conversation->data;
        })->toArray();

        return [
            'success' => true,
            'data' => $conversations,
            'lastActivityAt' => collect($conversations)->min('lastActivityTcp'),
        ];
    }


    /**\
     * @param array $data
     * @return array
     */
    public static function storeMessage(array $data): array
    {
        if ($data['success'] && isset($data['data'])) {
            return [
                'entityUrn' => explode(':', $data['data']->data->value->backendEventUrn)[3],
                'date' => Carbon::createFromTimestampMsUTC($data['data']->data->value->createdAt)->toDateTimeString()
            ];
        }
        return [];
    }

    /**\
     * @param array $data
     * @return array
     */
    public static function profiles(array $data): array
    {


        if ($data['success'] && isset($data['data'])) {
            return (new Profiles($data))->initializ();

        }
        return [
            'success' => false
        ];
    }

    /**
     * @param array $data
     * @param bool $con
     * @return array
     */
    public static function invitations(array $data, bool $con = false): array
    {


        if (!$data['success'] || !isset($data['data']) || !count($data['data']->included)) {
            return [];
        }

        $invitation_type_key = 'com.linkedin.voyager.relationships.invitation.Invitation';

        $included = collect($data['data']->included)->groupBy('$type');

        return $included[$invitation_type_key]->map(function ($item) use ($con, $included) {

            $profile = $included[self::TYPE_MINI_PROFILE]->first(function ($profile) use ($item, $con) {
                if ($con) {
                    return $item->{'*fromMember'} === $profile->entityUrn;

                } else {
                    return $item->invitee->{'*miniProfile'} === $profile->entityUrn;
                }
            });

            $avatar = null;
            if (isset($profile->picture) && isset($profile->picture->artifacts) && count($profile->picture->artifacts) > 1) {
                $avatar = $profile->picture->rootUrl . $profile->picture->artifacts[1]->fileIdentifyingUrlPathSegment;
            }

            return [
                'profile' => [
                    'fullName' => $profile->firstName . ' ' . $profile->lastName,
                    'avatar' => $avatar ?? Constants::DEFAULT_AVATAR,
                    'occupation' => $profile->occupation
                ],
                'sharedSecret' => $item->sharedSecret,
                'entityUrn' => explode(':', $item->entityUrn)[3],
                'sentTime' => Carbon::createFromTimestampMsUTC($item->sentTime)->toDateTimeString()
            ];
        })->toArray();
    }


    /**
     * @param array $data
     * @param string $user_linkedin_entityUrn
     * @return array
     */
    public static function conversationsConnections(array $data, string $user_linkedin_entityUrn): array
    {
        if (!count($data['data']->included)) {
            return [
                'success' => false
            ];
        }

        $data = collect($data['data']->included)->groupBy('$type');

        $conversations = $data[self::TYPE_CONVERSATION]->map(function ($item) {

            return [
                'lastActivityAt' => $item->lastActivityAt,
                'entityUrn' => explode(':', $item->entityUrn)[3],
                'interlocutorEntityUrn' => Helper::searchInString($item->{'*participants'}[0], ',', ')')
            ];
        });

        $interlocutors = $data[self::TYPE_MINI_PROFILE]->map(function ($profile) use ($conversations) {

            $array = [
                'entityUrn' => explode(':', $profile->entityUrn)[3],
                'image' => $profile->picture ?? '',
                'firstName' => $profile->firstName ?? '',
                'lastName' => $profile->lastName ?? '',
                'publicIdentifier' => $profile->publicIdentifier ?? '',
                'occupation' => $profile->occupation ?? '',
            ];

            if (isset($profile->picture) && isset($profile->picture->artifacts) && count($profile->picture->artifacts) > 1) {
                $array['image'] = $profile->picture->rootUrl . $profile->picture->artifacts[1]->fileIdentifyingUrlPathSegment;
            }

            return [
                'connection' => $array,
                'conversation' => $conversations->first(function ($conversation) use ($array) {
                    return $conversation['interlocutorEntityUrn'] === $array['entityUrn'];
                }),
            ];
        });


        $filter =  $interlocutors->filter(function ($interlocutor) use ($user_linkedin_entityUrn) {
            return $user_linkedin_entityUrn !== $interlocutor['connection']['entityUrn'] && !is_null($interlocutor['conversation']) && $interlocutor['conversation']['interlocutorEntityUrn'] !== 'UNKNOWN';
        })->toArray();

        return [
            'lastActivityAt' => $conversations->min('lastActivityAt'),
            'success' => true,
            'data' => array_values($filter),

        ];

    }

    /**
     * @param array $data
     * @return array|false[]
     */
    public static function connections(array $data): array
    {

        if ($data['success'] && isset($data['data']) && count($data['data']->included)) {

            $profiles = collect($data['data']->included)->groupBy('$type')[self::IDENTITY_PROFILE_KEY];


            $profiles = $profiles->map(function ($profile) {
                $array = [
                    'lastName' => $profile->lastName ?? '',
                    'firstName' => $profile->firstName ?? '',
                    'publicIdentifier' => $profile->publicIdentifier ?? '',
                    'entityUrn' => explode(':', $profile->entityUrn)[3],
                    'occupation' => $profile->headline ?? '',
                    'image' => isset($profile->profilePicture),
                ];

                if (isset($profile->profilePicture) && isset($profile->profilePicture->displayImageReference->vectorImage) && count($profile->profilePicture->displayImageReference->vectorImage->artifacts) > 1) {
                    $array['image'] = $profile->profilePicture->displayImageReference->vectorImage->rootUrl . $profile->profilePicture->displayImageReference->vectorImage->artifacts[1]->fileIdentifyingUrlPathSegment;
                } else {
                    $array['image'] = null;
                }
                return [
                    'connection' => $array
                ];
            })->toArray();


            return [
                'success' => true,
                'paging' => $data['data']->data->paging,
                'data' => $profiles
            ];

        }

        return [
            'success' => false
        ];
    }

    /**
     * @param array $data
     * @param string $publicIdentifier
     * @return array
     */

    public static function getTrackingId(array $data, string $publicIdentifier): array
    {

        if ($data['success'] && isset($data['data']) && count($data['data']->included)) {
            $profiles = collect($data['data']->included)->groupBy('$type')[self::TYPE_MINI_PROFILE];

            $profile = $profiles->first(function ($item) use ($publicIdentifier) {
                return $item->entityUrn === 'urn:li:fs_miniProfile:' . $publicIdentifier;
            });

            return [
                'success' => $profile->trackingId ? true : false,
                'trackingId' => $profile->trackingId ?? ''
            ];
        }

        return [
            'success' => false
        ];
    }

    public static function newMessageEvent(array $data, string $login): array
    {
        $data = collect($data);

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
            'login' => $login,
            'message' => $message,
            'writer' => $writer,
            'conversation' => $conversation,
        ];
    }

    public static function newConversation(array $data): array
    {
        if ($data['success'] && isset($data['data']) && isset($data['data']->data)) {
            return [
                'entityUrn' => explode(':',$data['data']->data->value->conversationUrn)[3],
                'lastActivityAt' => Carbon::createFromTimestampMsUTC($data['data']->data->value->createdAt)->toDateTimeString(),
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }
}
