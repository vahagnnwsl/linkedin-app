<?php

namespace App\Console\Commands;


use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class GetConnectionInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetConnectionInfo';
    protected $connectionRepository;
    protected $accountRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->connectionRepository = new  ConnectionRepository();
        $this->accountRepository = new  AccountRepository();
    }

    /**
     * @return int
     */
    public function handle()
    {

        $connection = $this->connectionRepository->getUnParsedFirst();

        $account = $this->accountRepository->getRandomFirst();

        $proxy = $account->getRandomFirstProxy();

        $resp = Api::profile($account->login, $account->password, $proxy)->getProfile($account->entityUrn);

        if($connection){
            if ($resp['status']) {

                $this->connectionRepository->update($connection->id, [
                    'data' => $resp['data']->included,
                    'is_parsed' => $this->connectionRepository::$PARSED_STATUS,
                    'parsed_date' => Carbon::now()->toDateTimeString()
                ]);
            }
        } else {

            $this->connectionRepository->updateAll(['is_parsed' => $this->connectionRepository::$UNPARSED_STATUS]);
        }

        return 1;
    }
}
