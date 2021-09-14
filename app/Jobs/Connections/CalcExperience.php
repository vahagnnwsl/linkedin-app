<?php

namespace App\Jobs\Connections;


use App\Models\Account;
use App\Models\Connection;
use App\Models\Log;
use App\Models\Position;
use App\Repositories\ConnectionRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPUnit\Exception;

class CalcExperience implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
        ];
    }

    public function handle()
    {

        $positions = Position::all();

        $positions->map(function ($position) {
            if ($position->start_date) {
                try {
                    $this_month = Carbon::parse($position->start_date)->floorMonth();
                    $end_date = $position->end_date ?: Carbon::now();
                    $start_month = Carbon::parse($end_date)->floorMonth(); // returns 2019-06-01
                    $diff = $start_month->diffInMonths($this_month);
                    $position->update(['duration' => $diff]);
                } catch (\Exception $exception) {
                     \Illuminate\Support\Facades\Log::error($exception->getMessage());
                }
            }
        });
    }
}
