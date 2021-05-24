<?php

namespace App\Console\Commands;


use App\Jobs\SearchByKey;

use App\Jobs\SearchByKeyCompanies;
use App\Jobs\SyncKeyCompaniesJob;
use App\Repositories\KeyRepository;

use Illuminate\Console\Command;

class SearchConnectionsWithAllActiveKeysCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SearchConnectionsWithAllActiveKeysCompanies';

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

    protected $keyRepository;

    public function __construct()
    {
        $this->keyRepository = new KeyRepository();

        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle()
    {
        $keys = $this->keyRepository->getActives();


        $keys->map(function ($key) {

            $this->alert('KEY: '.$key->id);
            SearchByKeyCompanies::dispatch($key);
        });

        return 1;
    }
}
