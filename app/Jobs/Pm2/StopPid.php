<?php

namespace App\Jobs\Pm2;


use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class StopPid implements ShouldQueue
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


        $time1 = time();

        try {
            $resp = shell_exec('pm2 stop '.$this->account->login);;
            $time2 = time();
            Log::alert($this->account->login,[
                'stop'=>$resp,
                'time'=>  dump($time2-$time1)
            ]);
        }catch (\Exception $exception){
            Log::info($this->account->login,[
                'time'=>  dump(time()-$time1),
                'error'=>$exception->getMessage()
            ]);
        }



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
