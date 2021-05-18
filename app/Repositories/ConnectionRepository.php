<?php

namespace App\Repositories;

use App\Models\Connection;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ConnectionRepository extends Repository
{

    protected $companyRepository;

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
     * @param string $entityUrn
     * @return mixed
     */
    public function getIdByEntityUrn(string $entityUrn)
    {
        return $this->model()::where('entityUrn', $entityUrn)->first('id');
    }


    /**
     * @param array $data
     * @param int $account_id
     * @param bool $conversation
     * @param bool $distance
     * @param int $key_id
     * @param bool $company
     */
    public function updateOrCreateSelfAndConversationThoughCollection(array $data, int $account_id, bool $conversation = false, bool $distance = false, int $key_id = 0, bool $company = false)
    {

        collect($data)->map(function ($item) use ($account_id, $conversation, $distance, $key_id, $company) {

            $connection = $this->store(Arr::except($item['connection'], 'secondaryTitle'));

            if ($distance) {
                if (isset($item['connection']['secondaryTitle'])) {
                    if ($item['connection']['secondaryTitle'] === '1st') {
                        DB::table('account_connections')
                            ->updateOrInsert(
                                ['account_id' => $account_id, 'connection_id' => $connection->id],
                                ['account_id' => $account_id, 'connection_id' => $connection->id]
                            );
                    }
                }
            } else {
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


            if ($conversation) {
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

            if (!$company) {
                if ($connection->occupation) {

                    $chunks = preg_split('/(at|-|–)/', $connection->occupation, -1, PREG_SPLIT_NO_EMPTY);

                    if (count($chunks) > 1) {
                        $companyName = trim($chunks[count($chunks) - 1]);
                        $company = $this->companyRepository->updateOrCreate(['name' => $companyName], ['name' => $companyName]);
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
