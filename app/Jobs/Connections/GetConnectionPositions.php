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

class GetConnectionPositions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Account $account;
    protected Connection $linkedinUser;
    private ConnectionRepository $connectionRepository;
    private CompanyRepository $companyRepository;


    /**
     * @param Account $account
     * @param Connection $connection
     */
    public function __construct(Account $account, Connection $connection)
    {
        $this->account = $account;
        $this->linkedinUser = $connection;
        $this->connectionRepository = new ConnectionRepository();
        $this->companyRepository = new CompanyRepository();
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
        $positions = Api::profile($this->account)->getProfile($this->linkedinUser->entityUrn);
        $positions = \App\Linkedin\Responses\Connection::parse($positions, 'positions');

        if (count($positions)) {
            DB::beginTransaction();
            try {

                $this->linkedinUser->positions()->delete();

                array_map(function ($item) {

                    $companyId = null;
                    if ($item['companyUrn']) {
                        $companyId = $this->companyRepository->updateOrCreate(
                            [
                                'entityUrn' => $item['companyUrn']
                            ],
                            [
                                'name' => $item['companyName'],
                                'entityUrn' => $item['companyUrn'],
                                'is_parsed' => 1
                            ])->id;
                    }


                    $this->connectionRepository->addPosition($this->linkedinUser->id, $item, $companyId);
                }, $positions);
                $this->linkedinUser->update(['position_parsed_date' => Carbon::now()->toDateTimeString()]);
                DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error($exception->getMessage());
                DB::rollback();
            }
        }
    }
}
