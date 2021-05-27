<?php

namespace App\Console\Commands;


use App\Linkedin\Helper;
use App\Linkedin\Responses\Company;
use App\Linkedin\Responses\Invitation;
use App\Linkedin\Responses\Messages;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\AaccountsConversationsLimit;
use App\Models\Proxy;
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



        $account = Account::where('login', 'ghukasyan.05@gmail.com')->first();
//        $proxy = Proxy::first();

        $a = '2-MmU4NzdjOTgtZTc4MS00NDYzLTg2MDQtMzk0OThhNjFiN2IwXzAxMw\\\\\\\\\\\==fff';

        $res =  (new Messages((array)Api::conversation($account->login, $account->password)->getConversationMessages($a),$a))();



        File::put(storage_path('d.json'), json_encode($res));


        dd($res);
    }
}
