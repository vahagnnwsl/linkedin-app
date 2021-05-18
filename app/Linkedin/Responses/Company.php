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


class Company
{

    protected $data;

    protected $miniCompany = 'com.linkedin.voyager.entities.shared.MiniCompany';

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function __invoke()
    {

        if ($this->data['success'] && isset($this->data['data']) && count($this->data['data']->included)) {

            $included = $this->data['data']->included;

            $models = collect($included)->groupBy('$type')[$this->miniCompany];

            $companies = $models->map(function ($item) {
                $company = [
                    "name" => $item->name,
                    "entityUrn" => explode(':', $item->entityUrn)[3],
                ];

                try {

                    $company['image'] = $item->logo->rootUrl . $item->logo->artifacts[0]->fileIdentifyingUrlPathSegment;

                } catch (\Exception $exception) {
                    $company['image'] = Constants::DEFAULT_AVATAR;
                }
                return $company;
            });

            return [
                'success' => true,
                'paging' => $this->data['data']->data->paging,
                'data' => $companies
            ];

        }

        return [
            'success' => false
        ];
    }
}

