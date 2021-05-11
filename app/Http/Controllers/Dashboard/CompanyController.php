<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\SearchByKeyAndCompany;
use App\Jobs\ParseCompanies;
use App\Repositories\CompanyRepository;
use App\Repositories\KeyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
    /**
     * @var KeyRepository
     */
    protected $companyRepository;


    /**
     * IndexController constructor.
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $companies = $this->companyRepository->filter($request->all(),[],'name','asc');

        return view('dashboard.companies.index', compact('companies'));
    }


    /**
     * @return RedirectResponse
     */
    public function getInfo(): RedirectResponse
    {
        $account = Auth::user()->account;

        ParseCompanies::dispatch($account->id);

        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }


    /**
     * @param int $di
     * @return RedirectResponse
     */
    public function getConnections(int $di): RedirectResponse
    {

        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }

}
