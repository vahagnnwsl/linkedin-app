<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;


class Connection
{


    const EDUCATION_KEY = 'com.linkedin.voyager.identity.profile.Education';
    const SKILL_KEY = 'com.linkedin.voyager.identity.profile.Skill';
    const POSITION_KEY = 'com.linkedin.voyager.identity.profile.Position';


    public static function parse(array $data,$parse = null)
    {
        $resp = [];


        if ($data['success']) {
            $options = $data['data']->included;
            $options = collect($options)->groupBy('$type');

            foreach ($options as $key => $option) {



                if ($key === self::SKILL_KEY && $parse === 'skills') {
                    $resp = $option->map(function ($skill) {
                        return $skill->name;
                    })->toArray();
                }

                if ($key === self::POSITION_KEY && $parse === 'positions') {
                    $resp = $option->map(function ($position) {
                        return [
                            'title' => $position->title,
                            'companyName' => $position->companyName,
                            'companyUrn'=> $position->companyUrn,
                            'timePeriod' => [
                                'start' => $position->timePeriod && isset($position->timePeriod->startDate) ? $position->timePeriod->startDate->month . '/' . $position->timePeriod->startDate->year : null,
                                'end' => $position->timePeriod && isset($position->timePeriod->endDate) ? $position->timePeriod->endDate->month . '/' . $position->timePeriod->endDate->year : null
                            ]
                        ];
                    })->toArray();
                }
            }

        }

        return $resp;
    }
}

