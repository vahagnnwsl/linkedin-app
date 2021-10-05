<?php

namespace App\Console\Commands\Pm2;

use Illuminate\Console\Command;

class StopPid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:StopPid {--pid=}';

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

        $resp = shell_exec('pm2 stop '.$pid);
        dump($resp);
        return 1;
    }
}
