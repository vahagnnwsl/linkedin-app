<?php

namespace App\Console\Commands\Conversations;


use App\Models\Conversation;
use Illuminate\Console\Command;
use App\Exports\ConversationExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ExportConversations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        $conversations = Conversation::with('connection', 'account')->orderBy('lastActivityAt', 'DESC')->get();

        $conversations = $conversations->map(function ($conversation) {
            if (!$conversation->connection || !$conversation->account) {
                return null;
            }
            return [
                'account' => $conversation->account->id,
                'connection' => $conversation->connection->id,
                'hash' => $conversation->entityUrn,
                'status' => '',
            ];
        })->filter(function ($c) {
            return $c !== null;
        });

        Excel::store(new ConversationExport($conversations), 'conversations.xlsx', 'local');
        $this->alert(storage_path('app\conversations.xlsx'));

    }
}
