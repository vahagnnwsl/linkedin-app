<?php

namespace App\Console\Commands\Profiles;


use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class SearchProfileByKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SearchProfileByKey {key} {account_id}';

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

    public function searchPeople($account, $key)
    {
        $result = Response::profiles((array)Api::profile($account->login, $account->password)->searchPeople($key));

        if ($result['success']) {

            $this->searchPeople($account, $key);
        }
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        $key = $this->argument('key');
        $account = (new AccountRepository())->getById($this->argument('account_id'));
        $result = Response::profiles((array)Api::profile($account->login, $account->password)->searchPeople($key,50));


        File::put(storage_path('b.json'), json_encode($result));
        dd($key);
    }

}
