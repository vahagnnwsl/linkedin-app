<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;


class Connection
{


    const EDUCATION_KEY = 'com.linkedin.voyager.identity.profile.Education';
    const SKILL_KEY = 'com.linkedin.voyager.identity.profile.Skill';
    const POSITION_KEY = 'com.linkedin.voyager.identity.profile.Position';


    public static function parse(array $data)
    {
        $educations = [];
        $skills = [];
        $positions = [];

        if ($data['success']) {
            $options = $data['data']->included;
            $options = collect($options)->groupBy('$type');

            foreach ($options as $key => $option){
                if ($key === self::EDUCATION_KEY){
                    $educations = $option->map(function ($education) {

                        return [
                            'schoolName'=>$education->schoolName,
                            'degreeName'=>$education->degreeName,
                        ];
                    })->toArray();
                }

                if ($key === self::SKILL_KEY){
                    $skills = $option->map(function ($skill) {
                        return $skill->name;
                    })->toArray();
                }

                if ($key === self::POSITION_KEY){
                    $positions = $option->map(function ($position) {
                        return [
                            'title'=>$position->title,
                            'companyName'=>$position->companyName,
                        ];
                    })->toArray();
                }
            }

        }

        return [
            'positions'=>$positions,
            'skills'=>$skills,
            'educations'=>$educations,
        ];
    }
}

