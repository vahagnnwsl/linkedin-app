<?php

namespace App\Jobs\Connections;


use App\Linkedin\Api;
use App\Models\Account;
use App\Models\Connection;
use App\Models\Proxy;
use App\Repositories\SkillRepository;
use App\Repositories\ConnectionRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GetConnectionSkills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Account $account;
    protected Connection $linkedinUser;
    protected Proxy $proxy;
    private SkillRepository $skillRepository;
    private ConnectionRepository $connectionRepository;


    /**
     * @param Account $account
     * @param Connection $connection
     */
    public function __construct(Account $account, Connection $connection)
    {
        $this->account = $account;
        $this->linkedinUser = $connection;
        $this->proxy = $account->getRandomFirstProxy();
        $this->skillRepository = new SkillRepository();
        $this->connectionRepository = new ConnectionRepository();
    }

    public function handle()
    {
        $skills = Api::profile($this->account->login, $this->account->password)->getProfileSkills($this->linkedinUser->entityUrn);
        $skills = \App\Linkedin\Responses\Connection::parse($skills, 'skills');


        if (count($skills)) {
            DB::beginTransaction();
            try {

                $this->linkedinUser->skills()->sync([]);

                array_map(function ($item) {
                    $skill = $this->skillRepository->updateOrCreate(
                        [
                            'name' => $item['name']
                        ],
                        [
                            'name' => $item['name']
                        ]);
                    $this->connectionRepository->addSkill($this->linkedinUser->id, $skill->id, $item['likes_count']);
                }, $skills);
                $this->linkedinUser->update(['skill_parsed_date' => Carbon::now()->toDateTimeString()]);
                DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error($exception->getMessage());
                DB::rollback();
            }
        }
    }
}
