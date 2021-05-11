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

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function __invoke()
    {

        if ($this->data['success'] && isset($this->data['data']) && count($this->data['data']->included)) {

            $resp = $this->data['data']->included[0];

            $company = [
                "entityUrn" => explode(':', $resp->entityUrn)[3],
                "name" => $resp->name,
            ];

            if (isset($resp->logo) && isset($resp->logo->artifacts)) {
                $company['image'] = $resp->logo->rootUrl . $resp->logo->artifacts[0]->fileIdentifyingUrlPathSegment;

            }

            return [
                'success' => true,
                'data' => $company
            ];
        }

        return [
            'success' => false
        ];
    }
}

