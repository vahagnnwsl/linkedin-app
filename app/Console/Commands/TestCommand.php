<?php

namespace App\Console\Commands;


use App\Http\Repositories\LinkedinConversationRepository;
use App\Http\Repositories\LinkedinMessageRepository;
use App\Http\Repositories\UserRepository;
use App\Linkedin\Helper;
use App\Linkedin\Responses\Response;
use App\Models\Connection;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TestCommand';

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
     * @return int
     */
    public function handle()
    {

        $data =json_decode( json_decode(File::get(storage_path('l.json')))->message);

        dd($data);




    }
}
