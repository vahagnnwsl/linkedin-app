<?php

namespace App\Console\Commands;

use App\Linkedin\Helper;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use Illuminate\Console\Command;

class ParsCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ParsCompanies';

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
     */
    public function handle()
    {
        $companyRepository = new CompanyRepository();

        $connections = (new ConnectionRepository())->getAll();


         $array = [];

        foreach ($connections as $connection) {
            $chunks = preg_split('/(at|-|–)/', $connection->occupation,-1, PREG_SPLIT_NO_EMPTY);

            if (count($chunks)>1){
                $companyName  = trim($chunks[count($chunks)-1]);
                $companyRepository->updateOrCreate(['name'=>$companyName],['name'=>$companyName]);
            }

        }



        dd($array);
        return 1;
    }

}
