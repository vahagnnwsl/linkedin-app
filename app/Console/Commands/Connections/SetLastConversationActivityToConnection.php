<?php

namespace App\Console\Commands\Connections;


use App\Models\Connection;
use App\Models\Conversation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetLastConversationActivityToConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetLastConversationActivityToConnection';

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
    public function handle():void
    {
       $conversations  = Conversation::with('connection')->select('lastActivityAt','connection_id')->orderBy('lastActivityAt','DESC')->get();

        $conversations->map(function ($conversation) {
           if ($conversation->connection) {
               $conversation->connection->update(['lastActivityAt' => $conversation->lastActivityAt]);
           }
        });

    }
}
