<?php

namespace App\Jobs\Pm2;


use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartPid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    protected Account $account;


    /**
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        shell_exec('sudo pm2 start ' . storage_path('linkedin/' . $this->account->login . '.json'));
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
}