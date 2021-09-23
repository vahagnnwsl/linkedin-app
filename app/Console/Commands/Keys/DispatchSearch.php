<?php

namespace App\Console\Commands\Keys;


use App\Models\Key;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class DispatchSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SearchByKeys';

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

        $keys = Key::whereStatus(1)->get();
        $keys->map(function ($key) {
            \App\Jobs\Keys\DispatchSearch::dispatch($key);
        });
        return 1;
    }

}
