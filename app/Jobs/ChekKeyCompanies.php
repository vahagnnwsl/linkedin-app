<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Key;
use App\Models\Proxy;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Services\CompanyService;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChekKeyCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;

    protected $connectionService;

    protected $companyService;

    /**
     * ChekKeyCompanies constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;

        $this->connectionService = new ConnectionService();

        $this->companyService = new CompanyService();
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Key' => $this->key->name,
        ];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $noParsedCompanies = $this->key->noParsedCompanies;

        $noParsedCompanies->map(function ($company)  {

            $account = $this->key->getRandomRelation('accounts');

            $proxy = $account->getRandomFirstProxy();

            $this->companyService->getInfoFormLinkedinAndUpdate($company, $account, $proxy);

            sleep(3);

        });

    }
}
