<?php

namespace App\Console\Commands;


use App\Linkedin\Helper;
use App\Linkedin\Responses\Company;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Connection;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TestCommand';

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
     * @return int
     */
    public function handle()
    {
        $account = Account::where('login','ghukasyan.05@gmail.com')->first();
        $result = Response::profiles((array)Api::profile($account->login, $account->password)->searchPeople('react',0,3189499));

        File::put(storage_path('a.json'),json_encode($result));

        dd(12);
    }
}
