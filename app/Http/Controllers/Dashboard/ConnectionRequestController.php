<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Repositories\ConnectionRequestRepository;


class ConnectionRequestController extends Controller
{
    /**
     * @var ConnectionRequestRepository
     */
    protected $connectionRequestRepository;


    /**
     * IndexController constructor.
     * @param ConnectionRequestRepository $connectionRequestRepository
     */
    public function __construct(ConnectionRequestRepository $connectionRequestRepository)
    {
        $this->connectionRequestRepository = $connectionRequestRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index(){

        $requests = $this->connectionRequestRepository->paginate();

        return view('dashboard.connection-requests.index',compact('requests'));
    }



}
