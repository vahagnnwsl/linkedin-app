<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\Keys\DispatchSearch;
use App\Jobs\Keys\SearchByKey;
use App\Jobs\Keys\SearchByKeyCompanies;
use App\Jobs\LinkedinSearchByKey;
use App\Jobs\LinkedinSearchByKeyAndCountry;
use App\Jobs\SearchByKeyAndCompany;
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


class KeyController extends Controller
{
    /**
     * @var KeyRepository
     */
    protected KeyRepository $keyRepository;

    /**
     * @var CountryRepository
     */
    protected CountryRepository $countryRepository;

    /**
     * @var AccountRepository
     */
    protected AccountRepository $accountRepository;

    /**
     * @var ProxyRepository
     */
    protected ProxyRepository $proxyRepository;


    /**
     * IndexController constructor.
     * @param KeyRepository $keyRepository
     */
    public function __construct(KeyRepository $keyRepository, CountryRepository $countryRepository, AccountRepository $accountRepository, ProxyRepository $proxyRepository)
    {
        $this->keyRepository = $keyRepository;
        $this->countryRepository = $countryRepository;
        $this->accountRepository = $accountRepository;
        $this->proxyRepository = $proxyRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $keys = $this->keyRepository->paginate();
        $countries = $this->countryRepository->getAll();
        $accounts = $this->accountRepository->selectForSelect2('full_name', [ 'type' => $this->accountRepository::$TYPE_REAL]);

        return view('dashboard.keys.index', compact('keys', 'countries', 'accounts'));
    }


    /**
     * @param KeyRequest $request
     * @return RedirectResponse
     */
    public function store(KeyRequest $request): RedirectResponse
    {

        $data = $request->validated();

        $key = $this->keyRepository->store(Arr::except($data, ['proxies_id', 'accounts_id']));

        $this->keyRepository->syncAccounts($key->id, $data['accounts_id']);

        $this->putFlashMessage(true, 'Successfully created');

        return redirect()->route('keys.index');
    }


    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $key = $this->keyRepository->getById($id);

        $countries = $this->countryRepository->getAll();

        $accounts = $this->accountRepository->selectForSelect2('full_name', [ 'type' => $this->accountRepository::$TYPE_REAL]);


        return view('dashboard.keys.edit', compact('key', 'countries', 'accounts'));

    }


    /**
     * @param KeyRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(KeyRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();

        $this->keyRepository->update($id, Arr::except($data, ['proxies_id', 'accounts_id']));

        $this->keyRepository->syncAccounts($id, $data['accounts_id']);

        $this->putFlashMessage(true, 'Successfully updated');

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function search(int $id): RedirectResponse
    {
        $key = $this->keyRepository->getById($id);
        DispatchSearch::dispatch($key);
        $this->putFlashMessage(true, 'Successfully run job');
        return redirect()->back();

    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function searchByCompanies(int $id): RedirectResponse
    {
        $key = $this->keyRepository->getById($id);
        SearchByKeyCompanies::dispatch($key);
        $this->putFlashMessage(true, 'Successfully run job');
        return redirect()->back();
    }
}
