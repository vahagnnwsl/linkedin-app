<?php

namespace App\Jobs\Connections;


use App\Linkedin\Api;
use App\Models\Account;
use App\Models\Connection;
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
        $this->skillRepository = new SkillRepository();
        $this->connectionRepository = new ConnectionRepository();
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Connection' => $this->linkedinUser->fullName .' '. $this->linkedinUser->id,
            'Account' => $this->account->full_name ,
        ];
    }

    public function handle()
    {
        $skills = Api::profile($this->account)->getProfileSkills($this->linkedinUser->entityUrn);
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
