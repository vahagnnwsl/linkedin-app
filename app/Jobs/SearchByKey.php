<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Models\Key;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class SearchByKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Key
     */
    protected $key;


    protected $connectionService;


    /**
     * RunKeyJob constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
        $this->connectionService = new ConnectionService();
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 172800);

        $this->connectionService->search($this->key, [
            'conCompany' => true,
        ]);

        SyncKeyCompaniesJob::dispatch($this->key);

    }
}
