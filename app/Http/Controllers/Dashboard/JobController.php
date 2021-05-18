<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\LinkedinSearchByKey;
use App\Jobs\LinkedinSearchByKeyAndCountry;
use App\Jobs\SearchByKeyAndCompany;
use App\Models\Job;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class JobController extends Controller
{


    public function __construct()
    {

    }


    /**
     * @return Application|Factory|View
     */
    public function index(){


        $jobs = Job::paginate(15);

        return view('dashboard.jobs.index',compact('jobs'));
    }



}
