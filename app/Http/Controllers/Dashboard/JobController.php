<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FailedJob;
use App\Models\Job;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;



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
