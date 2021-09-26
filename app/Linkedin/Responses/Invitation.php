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


class Invitation
{

    protected $data;

    const TYPE_KEY = '$type';
    const KEY_INVITATION = 'com.linkedin.voyager.relationships.invitation.Invitation';
    const KEY_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';


    public static function invoke(array $data): array
    {

        if ($data['success'] && isset($data['data']) && count($data['data']->included)) {

            $data = collect($data['data']->included);

            $invitations = $data->filter(function ($invitation) {
                return $invitation->{self::TYPE_KEY} === self::KEY_INVITATION;
            });

            $profiles = $data->filter(function ($profile) {
                return $profile->{self::TYPE_KEY} === self::KEY_MINI_PROFILE;
            });

            $invitations = $invitations->map(function ($invitation) use ($profiles) {

                $profile = $profiles->first(function ($profile) use ($invitation) {
                    return $profile->entityUrn === "urn:li:fs_miniProfile:" . $invitation->toMemberId;
                });

                $connection = [
                    'lastName' => $profile->lastName,
                    'firstName' => $profile->firstName,
                    'entityUrn' => $invitation->toMemberId,
                    'publicIdentifier' => $profile->publicIdentifier,
                    'occupation' => $profile->occupation,
                ];

                $connection['image'] = Constants::DEFAULT_AVATAR;

                if (isset($profile->picture) && isset($profile->picture->artifacts) && count($profile->picture->artifacts) > 1) {
                    $connection['image'] = $profile->picture->rootUrl . $profile->picture->artifacts[1]->fileIdentifyingUrlPathSegment;
                }

                return [
                    'created_at' => Carbon::createFromTimestampMsUTC($invitation->sentTime)->toDateTimeString(),
                    'message' => $invitation->message,
                    'connection' => $connection
                ];
            });

            return [
                'success' => true,
                'data' => [...array_values($invitations->toArray())]
            ];
        }

        return [
            'success' => false
        ];
    }
}

