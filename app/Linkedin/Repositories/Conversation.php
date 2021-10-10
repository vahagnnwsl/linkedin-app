<?php


namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\File;


class Conversation extends Repository
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
     * @param array $query_params
     * @return array
     */
    public function getConversations(array $query_params = []): array
    {

        $query_params['keyVersion'] = 'LEGACY_INBOX';

        return $this->client->setHeaders($this->account)->get(Constants::API_URL . '/messaging/conversations', $query_params);
    }

    /**
     * @param string $conversation_urn_id
     * @return array
     */
    public function getConversation(string $conversation_urn_id): array
    {
        return $this->client->setHeaders($this->account)->get(Constants::API_URL . '/messaging/conversations/' . $conversation_urn_id, ['keyVersion' => 'LEGACY_INBOX']);
    }

    /**
     * @param string $conversation_urn_id
     * @param array $query_params
     * @return array
     */
    public function getConversationMessages(string $conversation_urn_id, array $query_params = []): array
    {

        $query_params['keyVersion'] = 'LEGACY_INBOX';

        return $this->client->setHeaders($this->account)->get(Constants::API_URL . '/messaging/conversations/' . $conversation_urn_id . '/events', $query_params);
    }


    /**
     * @param string $message
     * @param string $conversation_urn_id
     * @return array
     * @throws GuzzleException
     */
    public function writeMessage(string $message, string $conversation_urn_id): array
    {

        $payload = [
            'eventCreate' => [
                'value' => [
                    'com.linkedin.voyager.messaging.create.MessageCreate' => [
                        'body' => $message,
                        'attachments' => [],
                        'attributedBody' => [
                            'text' => $message,
                            'attributes' => []
                        ],
                        'mediaAttachments' => []
                    ]
                ]
            ],
        ];


        return $this->client->setHeaders($this->account)->post(Constants::API_URL . '/messaging/conversations/' . $conversation_urn_id . '/events?action=create', $payload);

    }

    /**
     * @param string $conversation_urn_id
     * @return array
     * @throws GuzzleException
     */
    public function markConversationAsSeen(string $conversation_urn_id): array
    {
        $payload = [
            'patch' => [
                '$set' => [
                    'read' => true
                ]
            ]
        ];

        return $this->client->setHeaders($this->account)->post(Constants::API_URL . '/messaging/conversations/' . $conversation_urn_id, $payload);
    }


    /**
     * @return array
     * @throws GuzzleException
     */
    public function markAllItemsAsSeen(): array
    {
        $payload = [
            'until' => 1000 * Carbon::now()->timestamp
        ];

        return $this->client->setHeaders($this->account)->post(Constants::API_URL . '/messaging/badge?', $payload, ['action' => 'markAllItemsAsSeen']);
    }

    /**
     * @param string $message
     * @param string $connectionEntityUrn
     * @return array
     * @throws GuzzleException
     */
    public function createConversation(string $message, string $connectionEntityUrn): array
    {

        $message_event = [
            'eventCreate' => [
                'value' => [
                    'com.linkedin.voyager.messaging.create.MessageCreate' => [
                        'body' => $message,
                        'attachments' => [],
                        'attributedBody' => [
                            'text' => $message,
                            'attributes' => []
                        ],
                        'mediaAttachments' => []
                    ]
                ]
            ],
            'recipients' => [$connectionEntityUrn],
            'subtype' => "MEMBER_TO_MEMBER",
        ];

        $payload = [
            "keyVersion" => "LEGACY_INBOX",
            "conversationCreate" => $message_event,
        ];


        return $this->client->setHeaders($this->account)->post(Constants::API_URL . '/messaging/conversations?action=create', $payload);
    }

    /**
     * @param string $url
     * @return array
     */
    public function getFile(string $url)
    {
        return $this->client->setHeaders($this->account)->get($url, [], true);
    }

}
