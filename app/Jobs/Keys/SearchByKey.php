<?php

namespace App\Jobs\Keys;


use App\Models\Key;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchByKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Key
     */
    protected Key $key;


    protected ConnectionService $connectionService;


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

        $this->connectionService->search($this->key, [
            'conCompany' => true,
        ]);
    }
}
