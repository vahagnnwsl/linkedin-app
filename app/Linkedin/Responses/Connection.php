<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Api;
use App\Linkedin\Constants;
use App\Linkedin\Helper;
use App\Models\Account;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\File;

class Connection
{


    const EDUCATION_KEY = 'com.linkedin.voyager.identity.profile.Education';
    const SKILL_KEY = 'com.linkedin.voyager.identity.profile.Skill';
    const ENDORSED_SKILL_KEY = 'com.linkedin.voyager.identity.profile.EndorsedSkill';
    const POSITION_KEY = 'com.linkedin.voyager.identity.profile.Position';


    public static function parseOnlyGroupBy(array $data){
        $options = [];


        if ($data['success']) {
            $options = $data['data']->included;

            $options = collect($options)->groupBy('$type');


        }

        return $options;
    }

    public static function parse(array $data, $parse = null)
    {
        $resp = [];


        if ($data['success']) {
            $options = $data['data']->included;

            $options = collect($options)->groupBy('$type');

            foreach ($options as $key => $option) {


                if ($key === self::SKILL_KEY && $parse === 'skills') {
                    $endorsedSkills = $options[self::ENDORSED_SKILL_KEY];
                    $resp = $option->map(function ($skill) use ($endorsedSkills) {
                        return [
                            'name' => $skill->name,
//                            'entityUrn'=> Helper::searchInString($skill->entityUrn,':(',','),
                            'likes_count' => $endorsedSkills->first(function ($item) use ($skill) {
                                return $item->{'*skill'} === $skill->entityUrn;
                            })->endorsementCount,
                        ];
                    })->toArray();
                }

                if ($key === self::POSITION_KEY && $parse === 'positions') {

                    $resp = $option->map(function ($position) {

                        $startDate = $position->timePeriod && isset($position->timePeriod->startDate) && isset($position->timePeriod->startDate->year) ? Carbon::createFromDate($position->timePeriod->startDate->year, $position->timePeriod->startDate->month??1, 1) : null;
                        $endDate = $position->timePeriod && isset($position->timePeriod->endDate) && $startDate? Carbon::createFromDate($position->timePeriod->endDate->year, $position->timePeriod->endDate->month??1, 1) : null;
                        $companyUrn = explode('urn:li:fs_miniCompany:', $position->companyUrn);

                        return [
                            'name' => $position->title,
                            'companyName' => $position->companyName,
                            'companyUrn' => count($companyUrn) === 2 ? $companyUrn[1] : null,
                            'description' => $position->description,
                            'is_current' => !$endDate,
                            'start_date' => $startDate ? $startDate->toDateString() : null,
                            'end_date' => $endDate ? $endDate->toDateString() : null
                        ];
                    })->toArray();
                }
            }
        }

        return $resp;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function parseSingle(array $data): array
    {

        return [
            'entityUrn'=>  explode(':',$data['included'][0]->entityUrn)[3],
//            'publicIdentifier'=>  $data['included'][0]->publicIdentifier,
        ];
    }

    public static function careerInterest(array $data): bool
    {
        $careerInterest = false;
        if ($data['success'] && isset($data['data']->data) && isset($data['data']->data->elements) ) {
                foreach ($data['data']->data->elements as $element) {
                    if ($element->cardActionTracking === 'CAREER_INTEREST') {
                        $careerInterest = true;
                        break;
                    }
                }
        }
        return $careerInterest;
    }

    /**
     * @param Account $account
     * @param $id
     * @param $url
     * @return array|false[]
     */
    public static function getAndSaveImage(Account $account,string $url,string $name ): array
    {

        $resp = Api::connection($account)->getFile($url);


        if ($resp['success']) {
            if (!File::exists(storage_path('app/public/connections'))) {
                File::makeDirectory(storage_path('app/public/connections'));
            }
            if (!File::exists(storage_path('app/public/connections'))) {
                File::makeDirectory(storage_path('app/public/connections'));
            }

            file_put_contents(storage_path('app/public/connections/'.$name.'.jpg'), $resp['data']);
            return [
                'success' => true,
                'path' => '/storage/connections/'.$name.'.jpg'
            ];
        }
        return [
            'success' => false,
        ];
    }
}

