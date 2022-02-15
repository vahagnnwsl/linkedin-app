<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FailedJob;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class ErrorController extends Controller
{


    /**
     * @return Application|Factory|View
     */
    public function indexFailedJob()
    {


        $jobs = FailedJob::paginate(15);

        return view('dashboard.jobs.index', compact('jobs'));
    }


}
