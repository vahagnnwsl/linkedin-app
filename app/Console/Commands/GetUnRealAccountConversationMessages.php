<?php

namespace App\Console\Commands;


use App\Linkedin\Api;
use App\Models\Account;
use App\Models\User;
use App\Models\Conversation;

use App\Services\ConversationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nette\Utils\Image;

class GetUnRealAccountConversationMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetUnRealAccountConversationMessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle()
    {
        $user = User::first();
        $account = Account::whereId(1)->first();
        $conversation = Conversation::whereId(236)->first();

        $b = "https:\/\/www.linkedin.com\/dms\/C4D06AQHw6hsPPOdi0g\/messaging-attachmentFile\/0\/1633890129676?m=AQJ5Z_9FowJEkwAAAXxrgz0imxIqmSC0ad-OKRE0mu6N8kUUc3dXXCvyPg&ne=1&v=beta&t=x6pmG71o8n375_OSPTp9OdkDqO5Q4xl2BEU2M6LG4ro";
        $bc = "https://www.linkedin.com/dms/C4D06AQHw6hsPPOdi0g/messaging-attachmentFile/0/1633890129676?m=AQJ5Z_9FowJEkwAAAXxrgz0imxIqmSC0ad-OKRE0mu6N8kUUc3dXXCvyPg&ne=1&v=beta&t=x6pmG71o8n375_OSPTp9OdkDqO5Q4xl2BEU2M6LG4ro";

//        $a = Api::conversation($account)->getFile($bc);
////        File::put(storage_path('av.txt'),$a['data']);
////        $base64_str = substr($a['data'], strpos($a['data'], ",")+1);
////
////        //decode base64 string
////        $image = base64_decode($base64_str);
//        file_put_contents(storage_path('av.webp'), $a['data']);
        (new ConversationService())->getConversationMessages($user,$account,$conversation,false);

        return 1;
    }
}
