<?php

namespace App\Console\Commands\Helpers;


use App\Models\Conversation;
use App\Models\Position;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

class ChangeFolderNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ChangeFolderNames';

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
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        $conversations = Conversation::all();
        foreach ($conversations as $conversation) {
            $urn = $conversation->entityUrn;
            $md5 = md5($urn);
            try {
                if (File::exists(storage_path('app/public/conversations/' . $urn))) {
                    if (!File::exists(storage_path('app/public/conversations/' . $md5))) {
                        File::copyDirectory(storage_path('app/public/conversations/' . $urn), storage_path('app/public/conversations/' . $md5));
                    }
                }
            } catch (\Exception $exception) {
                dump($exception->getMessage());
                continue;
            }
        }

    }
}
