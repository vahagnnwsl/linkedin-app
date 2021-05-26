<?php

namespace App\Repositories;

use App\Linkedin\Repositories\Auth;
use App\Models\Connection;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ConnectionRepository extends Repository
{

    protected $companyRepository;
    public static $PARSED_STATUS = 1;
    public static $UNPARSED_STATUS = 0;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
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


        return $this->model()::when(isset($requestData['key']), function ($q) use ($requestData) {
            $q->where(function ($sub) use ($requestData) {
                $sub->where('firstName', 'LIKE', "%" . $requestData['key'] . "%")
                    ->orWhere('lastName', 'LIKE', "%" . $requestData['key'] . "%")
                    ->orWhere('occupation', 'LIKE', "%" . $requestData['key'] . "%");
            });

        })->when(count($requestData['enableKeysIdes']),function ($q) use ($requestData){

            $q->where(function ($q) use ($requestData) {

                $q->where(function ($sub) use ($requestData) {
                    $sub->whereHas('keys', function ($subQuery_1) use ($requestData) {
                        $subQuery_1->whereIn('keys.id', $requestData['enableKeysIdes']);
                    });
                })->orWhere(function ($subQuery) {
                    $subQuery->whereHas('accounts', function ($subQuery_1) {
                        $subQuery_1->where('accounts.id', Auth::user()->account->id);
                    });
                });

            });
        })->when(isset($requestData['keys_ids']) && count($requestData['keys_ids']),function ($q) use($requestData){
            $q->whereHas('keys', function ($subQuery_1) use ($requestData) {
                $subQuery_1->whereIn('keys.id', $requestData['keys_ids']);
            });
        })->orderby('id', 'desc')->paginate(20);
    }

    /**
     * @param array $data
     * @param int $account_id
     * @param int $key_id
     * @param bool $conDistance
     * @param bool $conCompany
     * @param bool $conConversation
     */
    public function updateOrCreateSelThoughCollection(array $data, int $account_id, int $key_id = 0, bool $conDistance = false, bool $conCompany = false, bool $conConversation = false)
    {

        collect($data)->map(function ($item) use ($account_id, $conConversation, $conDistance, $key_id, $conCompany) {

            $storeData = Arr::except($item['connection'], 'secondaryTitle');

            $connection = $this->updateOrCreate(['entityUrn' => $storeData['entityUrn']], $storeData);

            if ($conDistance) {
                DB::table('account_connections')
                    ->updateOrInsert(
                        ['account_id' => $account_id, 'connection_id' => $connection->id],
                        ['account_id' => $account_id, 'connection_id' => $connection->id]
                    );
            }

            if ($key_id !== 0) {
                DB::table('connections_keys')
                    ->updateOrInsert(
                        ['connection_id' => $connection->id, 'key_id' => $key_id],
                        ['connection_id' => $connection->id, 'key_id' => $key_id]
                    );
            }


            if ($conConversation) {
                (new ConversationRepository())->updateOrCreate(
                    [
                        'account_id' => $account_id,
                        'connection_id' => $connection->id,
                        'entityUrn' => $item['conversation']['entityUrn']
                    ],
                    [
                        'lastActivityAt' => Carbon::createFromTimestampMsUTC($item['conversation']['lastActivityAt'])->toDateTimeString()
                    ]
                );
            }

            if ($conCompany && $key_id !== 0) {
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
            }
        });
    }
}
