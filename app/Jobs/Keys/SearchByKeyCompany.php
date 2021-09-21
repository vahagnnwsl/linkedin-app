<?php

namespace App\Jobs\Keys;

use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Company;
use App\Models\Key;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class SearchByKeyCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Key
     */
    protected Key $key;

    /**
     * @var Company
     */
    protected Company $company;


    /**
     * @var ConnectionService
     */
    protected ConnectionService $connectionService;

    /**
     * @var Account
     */
    protected Account $account;

    /**
     * @param Key $key
     * @param Company $company
     */
    public function __construct(Key $key,Account $account,Company $company)
    {
        $this->key = $key;
        $this->company = $company;
        $this->account = $account;
        $this->connectionService = new ConnectionService();
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->connectionService->search($this->key,$this->account, [
            'companyEntityUrn' => $this->company->entityUrn,
        ]);
    }


    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Key' => $this->key->name,
            'Company' => 'Company: '.$this->company->name.'| ID: '.$this->company->id,
            'Account' => $this->account->full_name,
        ];
    }
}
