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


class Profile_2
{

    protected $data;
    const TYPE_KEY = '$type';
    const RESULTS_KEY = '*results';
    const EntityResultViewModel = 'EntityResultViewModel';
    const TYPE_BLENDED_SEARCH_CLUSTER = 'com.linkedin.voyager.search.BlendedSearchCluster';

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function __invoke(): array
    {
        File::put(storage_path('a.json'), json_encode($this->data));

        if ($this->data['success'] && isset($this->data['data']) && count($this->data['data']->included)) {

            $elements = $this->data['data']->data->elements;

            $filteredElements = [];

            foreach ($elements as $element) {

                if (isset($element->{self::RESULTS_KEY})) {

                    array_push($filteredElements, ...$element->{self::RESULTS_KEY});

                }
            }

            $included = $this->data['data']->included;

            $profiles = collect($included)->groupBy('$type');

            File::put(storage_path('b.json'), json_encode($profiles));


            $data = $profiles['com.linkedin.voyager.dash.search.EntityResultViewModel'];

            $data = collect($data)->map(function ($item) {

                $a = [
                    'occupation' => $item->primarySubtitle->text ?? '',
                    'firstName' => explode(' ', $item->title->text)[0] ?? '',
                    'lastName' => explode(' ', $item->title->text)[1] ?? '',
                    'entityUrn' => Helper::searchInString($item->entityUrn, 'fsd_profile:', ',SEARCH_SRP)'),
                    'distance' => $item->entityCustomTrackingInfo->memberDistance,
                ];


                try {
                    if (isset($item->image) && isset($item->image->attributes) && count($item->image->attributes)) {
                        $a['image'] = $item->image->attributes[0]->detailDataUnion->nonEntityProfilePicture->vectorImage->rootUrl . $item->image->attributes[0]->detailDataUnion->nonEntityProfilePicture->vectorImage->artifacts[0]->fileIdentifyingUrlPathSegment;
                    }
                } catch (\Exception $exception) {
                    $a['image'] = '';
                }

                return [
                    'connection' => $a
                ];

            })->toArray();


            return [
                'success' => true,
                'data' => $data
            ];
        }

        return [
            'success' => false
        ];
    }
}

