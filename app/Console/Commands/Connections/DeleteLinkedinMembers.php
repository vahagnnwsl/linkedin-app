<?php

namespace App\Console\Commands\Connections;


use App\Models\Connection;
use Illuminate\Console\Command;

class DeleteLinkedinMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DeleteLinkedinMembers';

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
        Connection::where([
            'firstName' =>'Linkedin',
            'lastName' =>'Member'
        ])->delete();

       $this->alert('SUCCESSFULLY DELETED');
    }
}
