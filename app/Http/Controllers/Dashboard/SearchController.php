<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\SearchByKeyAndCompany;
use App\Jobs\SyncLastMessagesForOneAccount;
use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Repositories\CompanyRepository;
use App\Repositories\KeyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class SearchController extends Controller
{
    /**
     * @var KeyRepository
     */
    protected $keyRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;


    /**
     * SearchController constructor.
     * @param KeyRepository $keyRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(KeyRepository $keyRepository, CompanyRepository $companyRepository)
    {
        $this->keyRepository = $keyRepository;
        $this->companyRepository = $companyRepository;
    }


    public function index()
    {

        $keys = $this->keyRepository->getAll();
        $companies = $this->companyRepository->getFiled();

        return view('dashboard.search.index', compact('keys', 'companies'));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function linkedin(Request $request): JsonResponse
    {
        $account = Auth::user()->account;

        SearchByKeyAndCompany::dispatch($request->get('key_id'),$request->get('company_id'),$account->id);

        $this->putFlashMessage(true, 'Request in process');

        return response()->json([]);
    }

}
