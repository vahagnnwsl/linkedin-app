<?php

namespace App\Console\Commands;


use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkedinJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkedinJson';


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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {

        $array = [];


        foreach ((new AccountRepository())->getAll() as $account) {
            array_push($array, [
                'login' => $account->login,
                'entityUrn' => $account->entityUrn,
            ]);
        }

        File::put(storage_path('linkedin/linkedin_users.json'),json_encode($array));

        return 1;
    }

}
