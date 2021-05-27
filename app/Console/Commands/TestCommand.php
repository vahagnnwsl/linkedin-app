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

//        $url = 'https://01c6de5caa46.ngrok.io';
//        $proxy = '64.120.85.2:40182';
//        $proxyauth = 'sexy4321:sexy654321';
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_PROXY, $proxy);
//        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 1);
//        $curl_scraped_page = curl_exec($ch);
//        curl_close($ch);
//
//        dd($curl_scraped_page,$ch);


//
//        $client = new \GuzzleHttp\Client([
//            'base_uri' => 'https://api.myip.com',
//            'proxy' => 'http://europe152535:europe455565@196.17.13.25:62817'
//
//        ]);
//        $res = $client->request('GET', '/');
//
//        dd($res->getBody()->getContents());

        $account = Account::where('login', 'ghukasyan.05@gmail.com')->first();
//        $proxy = Proxy::first();

        $a = '2-MmU4NzdjOTgtZTc4MS00NDYzLTg2MDQtMzk0OThhNjFiN2IwXzAxMw==';

        $res =  (new Messages((array)Api::conversation($account->login, $account->password)->getConversationMessages($a),$a))();



        File::put(storage_path('d.json'), json_encode($res));


        dd($res);
    }
}
