<?php

namespace App\Jobs\Connections;


use App\Linkedin\Api;
use App\Models\Account;
use App\Models\Connection;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GetConnectionCareerInterest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Account $account;
    protected Connection $linkedinUser;
    private ConnectionRepository $connectionRepository;


    /**
     * @param Account $account
     * @param Connection $connection
     */
    public function __construct(Account $account, Connection $connection)
    {
        $this->account = $account;
        $this->linkedinUser = $connection;
        $this->connectionRepository = new ConnectionRepository();
    }


    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Connection' => $this->linkedinUser->fullName . ' ' . $this->linkedinUser->id,
            'Account' => $this->account->full_name,
        ];
    }

    public function handle()
    {
        $careerInterest = \App\Linkedin\Responses\Connection::careerInterest(Api::profile($this->account)->getOpportunityCards($this->linkedinUser->entityUrn));
        $this->linkedinUser->update(['career_interest' => $careerInterest]);
    }
}
