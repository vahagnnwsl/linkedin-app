<?php

namespace App\Console\Commands;

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

        $connections->map(function ($item) use ($companyRepository) {

            $company_name = explode(' at ', $item->occupation);

            if (count($company_name) > 1) {
                $companyRepository->updateOrCreate(['name' => $company_name[1]], ['name' => $company_name[1]]);
            } else {
                $company_name = explode('-', $item->occupation);
                if (count($company_name) > 1) {
                    $companyRepository->updateOrCreate(['name' => $company_name[1]], ['name' => $company_name[1]]);
                }
            }

        });

        return 1;
    }

}
