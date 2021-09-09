<?php

namespace App\Console\Commands;


use App\Models\Position;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class CalcDurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CalcDurations';

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
    public function handle(): int
    {
        $positions = Position::all();
        $positions->map(function ($position) {
            if ($position->start_date) {
                $this_month = Carbon::parse($position->start_date)->floorMonth();
                $end_date = $position->end_date ?: Carbon::now();
                $start_month = Carbon::parse($end_date)->floorMonth(); // returns 2019-06-01
                $diff = $start_month->diffInMonths($this_month);
                $position->update(['duration'=>$diff]);
            }
        });
        return 1;
    }

}
