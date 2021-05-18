<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\GetCompaniesByCountryId;
use App\Jobs\SearchByKeyAndCompany;
use App\Jobs\ParseCompanies;
use App\Jobs\SyncCompaniesWithLinkedin;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
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
     * @var CountryRepository
     */
    protected $countryRepository;


    /**
     * CompanyController constructor.
     * @param CompanyRepository $companyRepository
     * @param CountryRepository $countryRepository
     */
    public function __construct(CompanyRepository $companyRepository,CountryRepository $countryRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->countryRepository = $countryRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $companies = $this->companyRepository->filter($request->all(),[],'name','asc');

        $countries = $this->countryRepository->getAll();

        return view('dashboard.companies.index', compact('companies','countries'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sync(Request $request): RedirectResponse
    {
        $account = Auth::user()->account;

        SyncCompaniesWithLinkedin::dispatch($account->id);

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
