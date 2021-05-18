<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\LinkedinSearchByKey;
use App\Jobs\LinkedinSearchByKeyAndCountry;
use App\Jobs\SearchByKeyAndCompany;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class KeyController extends Controller
{
    /**
     * @var KeyRepository
     */
    protected $keyRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;


    /**
     * IndexController constructor.
     * @param KeyRepository $keyRepository
     */
    public function __construct(KeyRepository $keyRepository,CountryRepository $countryRepository)
    {
        $this->keyRepository = $keyRepository;
        $this->countryRepository = $countryRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index(){

        $keys = $this->keyRepository->paginate();
        $countries = $this->countryRepository->getAll();

        return view('dashboard.keys.index',compact('keys','countries'));
    }


    /**
     * @param KeyRequest $request
     * @return RedirectResponse
     */
    public function store(KeyRequest $request): RedirectResponse
    {

        $this->keyRepository->store($request->validated());

        $this->putFlashMessage(true,'Successfully created');

        return redirect()->route('keys.index');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function search(Request $request): RedirectResponse
    {


        $account = Auth::user()->account;

        LinkedinSearchByKeyAndCountry::dispatch($request->get('key_id'),$request->get('country_id'),$account->id);

        $this->putFlashMessage(true,'Your request on process');

        return redirect()->route('keys.index');

    }
}
