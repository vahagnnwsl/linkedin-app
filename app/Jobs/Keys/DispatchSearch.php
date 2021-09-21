<?php

namespace App\Jobs\Keys;


use App\Linkedin\Api;
use App\Models\Key;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ReflectionProperty;

class DispatchSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Key
     */
    protected Key $key;


    /**
     * RunKeyJob constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $accounts = $this->key->accounts()->where(['status' => 1, 'type' => 1])->get();

        $accounts->map(function ($account) {
            $resp = Api::profile($account)->getOwnProfile();
            if ($resp['status'] === 200) {
                SearchByKey::dispatch($this->key, $account);
            }
        });
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Key' => $this->key->name,
        ];
    }
}
