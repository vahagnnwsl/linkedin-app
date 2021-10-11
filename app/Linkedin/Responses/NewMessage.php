<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Api;

use App\Linkedin\Helper;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class NewMessage
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var Account
     */
    protected Account $account;

    const TYPE_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';
    const TYPE_KEY = '$type';
    const MESSAGE_TYPE = 'com.linkedin.voyager.messaging.Event';

    /**
     * @param array $data
     * @param Account $account
     */
    public function __construct(array $data, Account $account)
    {
        $this->data = $data;
        $this->account = $account;
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

            try {
                $resp = Api::conversation($this->account)->getFile($message['attachments']['reference']);
                if ($resp['success']) {
                    if (!File::exists(storage_path('app/public/conversations'))) {
                        File::makeDirectory(storage_path('app/public/conversations'));
                    }
                    if (!File::exists(storage_path('app/public/conversations/'.$conversation['entityUrn']))) {
                        File::makeDirectory(storage_path('app/public/conversations/'.$conversation['entityUrn']));
                    }
                    file_put_contents(storage_path('app/public/conversations/'.$conversation['entityUrn'].'/'.$message['attachments']['name']), $resp['data']);
                    $message['attachments']['filePath'] = '/storage/conversations/'.$conversation['entityUrn'].'/'.$message['attachments']['name'];
                }
            }catch (\Exception $exception){
                Log::error($conversation['entityUrn'],['error'=>$exception->getMessage()]);
            }
        }

        return [
            'login' => $this->account->login,
            'message' => $message,
            'writer' => $writer,
            'conversation' => $conversation
        ];
    }
}

