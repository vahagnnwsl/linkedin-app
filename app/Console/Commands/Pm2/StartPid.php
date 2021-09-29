<?php

namespace App\Console\Commands\Pm2;

use Illuminate\Console\Command;

class StartPid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:StartPid {--pid=}';

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
        $pid = $this->option('pid');

        shell_exec('pm2 start ' . storage_path('linkedin/' . $pid . '.json'));

        return 1;
    }
}
