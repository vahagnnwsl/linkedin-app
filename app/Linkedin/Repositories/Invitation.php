<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Invitation extends Repository
{

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * @param string $profile_id
     * @param string $tracking_id
     * @param string $message
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendInvitation(string $profile_id, string $tracking_id, string $message): array
    {
        $payload = [
            'emberEntityName' => 'growth/invitation/norm-invitation',
            'invitee' => [
                'com.linkedin.voyager.growth.invitation.InviteeProfile' => [
                    'profileId' => $profile_id
                ]
            ],
            'message' => $message,
            'trackingId' => $tracking_id
        ];

        return  $this->client->setHeaders($this->login)->post(Constants::API_URL . '/growth/normInvitations', $payload);
    }

    /**
     * @return array
     */
    public function getSentInvitations(): array
    {
        $query_params = [
            'start' => 0,
            'count' => 100,
            'invitationType' => 'CONNECTION',
            'q' => 'invitationType'
        ];

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/relationships/sentInvitationViewsV2', $query_params);
    }

    /**
     * @return array
     */
    public function getReceivedInvitations(): array
    {
        $query_params = [
            'start' => 0,
            'count' => 100,
            'q' => 'receivedInvitation'
        ];

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/relationships/invitationViews', $query_params);
    }

    /**
     * @param int $invitation_id
     * @param string $invitation_shared_secret
     * @param string $action
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function replyInvitation(int $invitation_id, string $invitation_shared_secret, string $action = 'accept'): array
    {

        $payload = [
            "invitationId" => $invitation_id,
            "invitationSharedSecret" => $invitation_shared_secret,
            "isGenericInvitation" => false
        ];

        return $this->client->setHeaders($this->login)->post(Constants::API_URL . '/relationships/invitations/' . $invitation_id . '?action=' . $action, $payload, ['action' => $action]);
    }
}
