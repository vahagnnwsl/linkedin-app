<?php

namespace App\Console\Commands\Accounts;


use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class SyncFriends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SyncFriends {account_id}';

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
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): int
    {
        $account = (new AccountRepository())->getById($this->argument('account_id'));

        $resp = Api::profile($account->login, $account->password)->getProfileConnections($account->entityUrn);

        File::put(storage_path('a.json'),json_encode($resp));
        return 1;
    }

}
