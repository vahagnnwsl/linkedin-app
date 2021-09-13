<?php

namespace App\Jobs\Connections;


use App\Models\Account;
use App\Models\Connection;
use App\Repositories\ConnectionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConnectionsPositions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ConnectionRepository $connectionRepository;
    private Account $account;

    /**
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->connectionRepository = new ConnectionRepository();
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Account' => $this->account->full_name,
        ];
    }

    public function handle()
    {
        $connections = $this->connectionRepository->getAvailableRecordForParsingPositions();

        $connections->map(function ($connection) {
            GetConnectionPositions::dispatch($this->account, $connection);
        });
    }
}
