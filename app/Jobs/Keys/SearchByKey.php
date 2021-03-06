<?php

namespace App\Jobs\Keys;


use App\Models\Account;
use App\Models\Key;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ReflectionProperty;

class SearchByKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Key
     */
    protected Key $key;

    /**
     * @var Account
     */
    protected Account $account;


    protected ConnectionService $connectionService;


    /**
     * RunKeyJob constructor.
     * @param Key $key
     */
    public function __construct(Key $key,Account $account)
    {
        $this->key = $key;
        $this->account = $account;
        $this->connectionService = new ConnectionService();
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->connectionService->search($this->key,$this->account, [
            'conCompany' => true,
        ]);
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Key' => $this->key->name,
            'Account' => $this->account->full_name,
        ];
    }
}
