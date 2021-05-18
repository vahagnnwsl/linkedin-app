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


class Profiles
{
    const TYPE_KEY = '$type';
    const TYPE_MINI_PROFILE = 'com.linkedin.voyager.identity.shared.MiniProfile';
    const TYPE_BLENDED_SEARCH_CLUSTER = 'com.linkedin.voyager.search.BlendedSearchCluster';

    /**
     * @var object
     */
    protected $data;

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
     *
     */
    public function __construct(array $data)
    {
        $this->data = $data['data'];
    }

    /**
     * @return array
     */
    public function initializ(): array
    {
        $profiles = $this->data->included;

        $profiles = collect($profiles)->groupBy('$type');

        if (!isset($profiles[self::TYPE_MINI_PROFILE])) {
            return [
                'success' => false
            ];
        }

        $profiles = $profiles[self::TYPE_MINI_PROFILE];

        $profiles = $profiles->map(function ($profile) {

            $array = [
                'firstName' => $profile->firstName ?? '',
                'lastName' => $profile->lastName ?? '',
                'publicIdentifier' => $profile->publicIdentifier ?? '',
                'occupation' => $profile->occupation ?? '',
                'entityUrn' => explode(':', $profile->entityUrn)[3],
            ];

            if (isset($profile->picture) && isset($profile->picture->artifacts) && count($profile->picture->artifacts) > 1) {
                $array['image'] = $profile->picture->rootUrl . $profile->picture->artifacts[1]->fileIdentifyingUrlPathSegment;
            }

            return $array;
        })->toArray();

        $elements = $this->data->data->elements;

        $filteredElements = [];

        foreach ($elements as $element) {

            if ($element->{self::TYPE_KEY} === self::TYPE_BLENDED_SEARCH_CLUSTER) {

                if (isset($element->elements) && count($element->elements)) {
                    array_push($filteredElements, ...$element->elements);
                }
            }
        }

        $result = collect($filteredElements)->map(function ($item) use ($profiles) {

            $entityUrn = explode(':', $item->targetUrn)[3];

            $profile = collect($profiles)->first(function ($i) use ($entityUrn) {
                return $entityUrn === $i['entityUrn'];
            });

            if ($profile) {

                $profile['secondaryTitle'] = $item->secondaryTitle->text ?? '';
                return ['connection' => $profile];
            }

            return [];

        })->reject(function ($item) {
            return empty($item);
        })->toArray();


        return [
            'success' => true,
            'paging' => $this->data->data->paging,
            'data' => $result
        ];
    }
}
