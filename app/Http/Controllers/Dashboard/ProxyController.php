<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Http\Requests\ProxyRequest;
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class ProxyController extends Controller
{

    /**
     * @var ProxyRepository
     */
    protected $proxyRepository;


    /**
     * IndexController constructor.
     * @param ProxyRepository $proxyRepository
     */
    public function __construct(ProxyRepository $proxyRepository)
    {

        $this->proxyRepository = $proxyRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $proxies = $this->proxyRepository->paginate();

        return view('dashboard.proxies.index', compact('proxies'));
    }


    /**
     * @param ProxyRequest $request
     * @return RedirectResponse
     */
    public function store(ProxyRequest $request): RedirectResponse
    {

        $this->proxyRepository->store($request->validated());

        $this->putFlashMessage(true, 'Successfully created');

        return redirect()->route('proxies.index');
    }

    /**
     * @param ProxyRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(ProxyRequest $request, int $id): RedirectResponse
    {

        $this->proxyRepository->update($id, $request->validated());

        $this->putFlashMessage(true, 'Successfully created');

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {

        $proxy = $this->proxyRepository->getById($id);

        return view('dashboard.proxies.edit', compact('proxy'));
    }
}
