<?php

namespace App\Repositories;

use App\Models\AaccountsConversationsLimit;
use App\Models\Category;
use App\Models\Connection;
use App\Models\Key;
use App\Models\Message;
use App\Models\Position;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ConnectionRepository extends Repository
{

    protected CompanyRepository $companyRepository;
    protected MessageRepository $messageRepository;
    protected ConversationRepository $conversationRepository;
    public static int $PARSED_STATUS = 1;
    public static int $UNPARSED_STATUS = 0;


    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
        $this->conversationRepository = new ConversationRepository();
        $this->messageRepository = new MessageRepository();
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return Connection::class;
    }

    /**
     * @param array $data
     */
    public function updateAll(array $data)
    {
        $this->model()::update($data);
    }

    /**
     * @return mixed
     */
    public function getUnParsedFirst()
    {
        return $this->model()::where('is_parsed', self::$UNPARSED_STATUS)->first();
    }

    /**
     * @param string $entityUrn
     * @return mixed
     */
    public function getIdByEntityUrn(string $entityUrn)
    {
        return $this->model()::where('entityUrn', $entityUrn)->first('id');
    }

    public function filter(array $requestData, string $orderBy = 'created_at', string $direction = 'desc')
    {

        return $this->model()::when(isset($requestData['key']), function ($query) use ($requestData) {
            $query
                ->when(isset($requestData['search_in']) && count($requestData['search_in']) > 0 && in_array('occupation', $requestData['search_in']), function ($q) use ($requestData) {
                    $q->where('occupation', 'LIKE', "%" . $requestData['key'] . "%");
                })
                ->when(isset($requestData['search_in']) && count($requestData['search_in']) > 0 && in_array('skills', $requestData['search_in']), function ($q) use ($requestData) {
                    $q->whereHas('skills', function ($subQ) use ($requestData) {
                        $subQ->where('skills.name', 'LIKE', '%' . $requestData['key'] . '%');
                    });
                })->when(isset($requestData['positions']), function ($q) use ($requestData) {
                    $q->whereHas('positions', function ($subQ) use ($requestData) {
                        $type = $requestData['positions'] === 'all' ? [0, 1] : [1];
                        $subQ
                            ->whereIn('is_current', $type)
                            ->where('positions.name', 'LIKE', '%' . $requestData['key'] . '%')
                            ->when(isset($requestData['experience']) && $requestData['experience'] > 0, function ($sub_q) use ($requestData) {
                                $sub_q->select(DB::raw('SUM(duration)'))->having(DB::raw('SUM(duration)'), '>=', $requestData['experience'] * 12);
                            });
                    });
                })->when(isset($requestData['statuses']), function ($q) use ($requestData) {
                    $q->whereHas('statuses', function ($subQ) use ($requestData) {
                        $type = $requestData['statuses'] === 'all' ? [0, 1] : [1];
                        $subQ->where('statuses.comment', 'LIKE', '%' . $requestData['key'] . '%')->whereIn('is_last', $type);
                    });
                });
        })->when(isset($requestData['keys_ids']) && count($requestData['keys_ids']) > 0, function ($query) use ($requestData) {
            $query->whereHas('keys', function ($subQuery) use ($requestData) {
                $subQuery->whereIn('keys.id', $requestData['keys_ids']);
            });
        })->when(isset($requestData['categories']) && count($requestData['categories']) > 0, function ($query) use ($requestData) {
            $query->whereHas('statuses', function ($subQuery) use ($requestData) {
                $ids = $requestData['categories'];
                $subQuery->whereIn('statuses.category_id', DB::table('categories')->select('id')->whereIn('id', $ids)->orWhereIn('parent_id', $ids)->pluck('id'));
            });
        })->when(isset($requestData['companies']) && count($requestData['companies']) > 0, function ($query) use ($requestData) {
            $query->whereHas('positions', function ($subQuery) use ($requestData) {
                $subQuery->whereIn('positions.company_id', $requestData['companies']);
            });
        })->when(isset($requestData['name']), function ($query) use ($requestData) {
            $query->where('firstName', 'LIKE', "%" . $requestData['name'] . "%")
                ->orWhere('lastName', 'LIKE', "%" . $requestData['name'] . "%")
                ->orWhere(DB::raw(' CONCAT(firstName," ", lastName)'), 'LIKE', "%" . $requestData['name'] . "%")
                ->orWhere(DB::raw(' CONCAT(lastName," ", firstName)'), 'LIKE', "%" . $requestData['name'] . "%");
        })->when(!isset($requestData['key']) && isset($requestData['experience']) && $requestData['experience'] > 0, function ($query) use ($requestData) {
            $query->whereHas('positions', function ($subQ) use ($requestData) {
                $subQ
                    ->where('positions.name', 'LIKE', '%' . $requestData['key'] . '%')
                    ->select(DB::raw('SUM(duration)'))->having(DB::raw('SUM(duration)'), '>=', $requestData['experience'] * 12);
            });
        })->when(isset($requestData['accounts']) && count($requestData['accounts']) > 0, function ($query) use ($requestData) {
            $query->whereHas('accounts', function ($subQuery) use ($requestData) {
                $subQuery->whereIn('accounts.id', $requestData['accounts']);
            });
        })->when(isset($requestData['distance']), function ($query) use ($requestData) {
            if ($requestData['distance'] === 'no_accounts') {
                $query->doesnthave('accounts');
            } else if ($requestData['distance'] === 'accounts') {
                $query->whereHas('accounts');
            }elseif($requestData['distance'] === 'accounts_with_keys'){
                $query->whereHas('accounts')->whereHas('keys');
            }
        })->orderby('id', 'desc')->paginate(20);
    }

    /**
     * @param int $account_id
     * @param int $connection_id
     */
    public function canSendConnectionRequest(int $account_id, int $connection_id)
    {

        $conversationIds = $this->getById($connection_id)->conversations()->pluck('id')->toArray();


        if (count($conversationIds)) {

            $message = Message::whereIn('conversation_id', $conversationIds)->orderByDesc('date')->first();

            if ($message) {
                $from = date('Y-m-d h:i:s', strtotime($message->date));
                $to = date('Y-m-d h:i:s');

                $nodays = (strtotime($to) - strtotime($from)) / (60 * 60 * 24); //it will count no. of days

                if ($nodays >= 10) {

                    return true;
                }

                return false;
            }

        }

        return true;
    }


    /**
     * @param $id
     * @param $skill_id
     * @param int $like_count
     * @return bool
     */
    public function addSkill($id, $skill_id, int $like_count = 0): bool
    {
        return DB::table('connection_skills')->insert([
            'connection_id' => $id,
            'skill_id' => $skill_id,
            'like_count' => $like_count,
        ]);
    }


    /**
     * @param $connectionId
     * @param null $companyId
     * @param $data
     * @return mixed
     */
    public function addPosition($connectionId, $data, $companyId = null): Position
    {
        return Position::create(array_merge(Arr::except($data, ['companyUrn', 'companyName']),
            [
                'connection_id' => $connectionId,
                'company_id' => $companyId
            ]
        ));
    }

    /**
     * @return Collection
     */
    public function getAvailableRecordForParsingSkills(): Collection
    {
        $currentDay = Carbon::now()->format('d');
        return $this->model()::whereDay('skill_parsed_date', '!=', $currentDay)->orWhere('skill_parsed_date', null)->get();
    }

    /**
     * @return Collection
     */
    public function getAvailableRecordForParsingPositions(): Collection
    {
        $currentDay = Carbon::now()->format('d');
        return $this->model()::whereDay('position_parsed_date', '!=', $currentDay)->orWhere('position_parsed_date', null)->get();
    }

    /**
     * @param array $data
     * @param int $connection_id
     * @return Status
     */
    public function addStatus(array $data, int $connection_id): Status
    {
        Status::where('connection_id', $connection_id)->update(['is_last' => 0]);
        $data['connection_id'] = $connection_id;
        $data['is_last'] = 1;
        return Status::create($data);
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Category::get();
    }


    /**
     * @param array $resp
     * @param int $account_id
     */
    public function updateOrCreateConnections(array $resp, int $account_id)
    {
        collect($resp)->map(function ($item) use ($account_id) {
            DB::beginTransaction();
            try {
                $connection = $this->updateOrCreate(['entityUrn' => $item['connection']['entityUrn']], $item['connection']);
                DB::table('account_connections')
                    ->updateOrInsert(
                        ['account_id' => $account_id, 'connection_id' => $connection->id],
                        ['account_id' => $account_id, 'connection_id' => $connection->id]
                    );
                DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error($exception->getMessage());
                DB::rollback();
            }
        });
    }

    /**
     * @param array $resp
     * @param int $account_id
     * @param int $key_id
     */
    public function updateOrCreateConnectionsOnTimeKeySearch(array $resp, int $account_id, int $key_id)
    {
        collect($resp)->map(function ($item) use ($account_id, $key_id) {
            DB::beginTransaction();
            try {
                $connection = $this->updateOrCreate(['entityUrn' => $item['connection']['entityUrn']], $item['connection']);

                DB::table('connections_keys')
                    ->updateOrInsert(
                        ['connection_id' => $connection->id, 'key_id' => $key_id],
                        ['connection_id' => $connection->id, 'key_id' => $key_id]
                    );

                if ($connection->occupation) {

                    $chunks = preg_split('/(at|-|–)/', $connection->occupation, -1, PREG_SPLIT_NO_EMPTY);

                    if (count($chunks) > 1) {
                        $companyName = trim($chunks[count($chunks) - 1]);

                        $company = $this->companyRepository->getByName($companyName);

                        if (!$company) {
                            $company = $this->companyRepository->store(['name' => $companyName]);
                        }


                        DB::table('company_search_keys')
                            ->updateOrInsert(
                                ['key_id' => $key_id, 'company_id' => $company->id],
                                ['key_id' => $key_id, 'company_id' => $company->id]
                            );
                    }
                }

                DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error($exception->getMessage());
                DB::rollback();
            }
        });
    }

    public function updateOrCreateConversation(array $resp, int $account_id)
    {

        collect($resp)->map(function ($item) use ($account_id) {
            DB::beginTransaction();
            try {
                $connection = $this->updateOrCreate(['entityUrn' => $item['connection']['entityUrn']], $item['connection']);
                DB::table('account_connections')
                    ->updateOrInsert(
                        ['account_id' => $account_id, 'connection_id' => $connection->id],
                        ['account_id' => $account_id, 'connection_id' => $connection->id]
                    );
                $this->conversationRepository->updateOrCreate(
                    [
                        'account_id' => $account_id,
                        'connection_id' => $connection->id,
                        'entityUrn' => $item['conversation']['entityUrn']
                    ],
                    [
                        'lastActivityAt' => Carbon::createFromTimestampMsUTC($item['conversation']['lastActivityAt'])->toDateTimeString()
                    ]
                );
                DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error($exception->getMessage());
                DB::rollback();
            }
        });
    }

    /**
     * @param int $id
     * @param array $dataKeys
     */
    public function addKeys(int $id, array $dataKeys)
    {
        $this->model()::whereId($id)->first()->keys()->sync($dataKeys);
    }
}
