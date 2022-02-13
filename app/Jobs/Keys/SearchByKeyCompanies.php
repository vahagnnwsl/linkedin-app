<?php

namespace App\Jobs\Keys;

use App\Linkedin\Api;
use App\Models\Key;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SearchByKeyCompanies implements ShouldQueue
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

        $parsedCompanies = $this->key->parsedCompanies;
        $accounts = $this->key->accounts()->where(['status' => 1, 'type' => 1])->get();

        $parsedCompanies->map(function ($company) use ($accounts) {
            $accounts->map(function ($account) use ($company) {
                $resp = Api::profile($account)->getOwnProfile();
                if ($resp['status'] === 200) {
                    SearchByKeyCompany::dispatch($this->key, $account, $company);
                }
            });
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
