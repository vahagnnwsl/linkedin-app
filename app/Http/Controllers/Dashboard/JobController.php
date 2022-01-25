<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\Keys\SearchByKey;
use App\Jobs\LinkedinSearchByKey;
use App\Jobs\LinkedinSearchByKeyAndCountry;
use App\Jobs\SearchByKeyAndCompany;
use App\Models\FailedJob;
use App\Models\Job;
use App\Repositories\AccountRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use App\Repositories\ProxyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class JobController extends Controller
{

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request){

        $type = $request->get('type') ?? 'process';
        if ($type === 'process'){
            $jobs = Job::paginate(50);
        }else{
            $jobs = FailedJob::orderBy('id','DESC')->paginate(50);
        }

        return view('dashboard.jobs.index', compact('jobs','type'));
    }

    public function delete(Request $request){
        $type = $request->get('type');
        $ids = $request->get('jobs');
        if ($type === 'process') {
            Job::destroy($ids);
        }else{
            FailedJob::destroy($ids);
        }
        $this->putFlashMessage(true, 'Successfully deleted');

        return response()->json();
    }
}
