<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Models\Account;
use App\Models\Company;
use App\Models\Proxy;
use App\Repositories\CompanyRepository;

class CompanyService
{


    protected $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
    }


    public function getInfoFormLinkedinAndUpdate(Company $company, Account $account,Proxy $proxy)
    {

        $resp = (new \App\Linkedin\Responses\Company(Api::company($account->login, $account->password,$proxy)->search($company->name)))();

        if ($resp['success']) {

            $data = $resp['data'][0];

            $data['is_parsed'] = $this->companyRepository::$PARSED_SUCCESS_STATUS;

        } else {

            $data['is_parsed'] = $this->companyRepository::$PARSED_FAILED_STATUS;
        }

        $company->update($data);

    }


}
