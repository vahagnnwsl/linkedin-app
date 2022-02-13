<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Helper;


class Profile_2
{

    protected $data;
    protected $account;
    const TYPE_KEY = '$type';
    const RESULTS_KEY = '*results';
    const EntityResultViewModel = 'EntityResultViewModel';
    const TYPE_BLENDED_SEARCH_CLUSTER = 'com.linkedin.voyager.search.BlendedSearchCluster';

    public function __construct(array $data, $account)
    {
        $this->data = $data;
        $this->account = $account;
    }


    public function __invoke(): array
    {

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


            $data = $profiles['com.linkedin.voyager.dash.search.EntityResultViewModel'];


            $data = collect($data)->map(function ($item) {

                $a = [
                    'occupation' => $item->primarySubtitle->text ?? '',
                    'firstName' => explode(' ', $item->title->text)[0] ?? '',
                    'lastName' => explode(' ', $item->title->text)[1] ?? '',
                    'entityUrn' => Helper::searchInString($item->entityUrn, 'fsd_profile:', ',SEARCH_SRP'),
                    'distance' => $item->entityCustomTrackingInfo->memberDistance,
                    'career_interest' => 0,
                ];


                try {
                    if (isset($item->image) && isset($item->image->attributes) && count($item->image->attributes)) {
                        $root = $item->image->attributes[0]->detailDataUnion->nonEntityProfilePicture->vectorImage->rootUrl;
                        $str = Helper::searchInString($root, 'profile-', 'shrink_');
                        if (isset($str) && $str === 'framedphoto-'){
                            $a['career_interest'] = 1;
                        }
                        $a['image'] = $root . $item->image->attributes[0]->detailDataUnion->nonEntityProfilePicture->vectorImage->artifacts[0]->fileIdentifyingUrlPathSegment;
                    }
                } catch (\Exception $exception) {}

                if (isset($a['image'])) {
                    $localImageReq = Connection::getAndSaveImage($this->account,$a['image'],$a['entityUrn'].'_'.time());
                    if ($localImageReq['success']){
                        $a['localImage'] = $localImageReq['path'];
                    }
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

