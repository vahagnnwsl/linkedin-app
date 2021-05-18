<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\KeyRequest;
use App\Jobs\SearchByKeyAndCompany;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class CountryController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;


    /**
     * IndexController constructor.
     * @param CountryRepository $countryRepository
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $countries = $this->countryRepository->paginate();

        return view('dashboard.countries.index', compact('countries'));
    }


    /**
     * @param CountryRequest $request
     * @return RedirectResponse
     */
    public function store(CountryRequest $request): RedirectResponse
    {

        $this->countryRepository->store($request->validated());

        $this->putFlashMessage(true, 'Successfully created');

        return redirect()->route('countries.index');
    }


    public function destroy(int $id)
    {
        $this->countryRepository->delete($id);
        $this->putFlashMessage(true, 'Successfully deleted');

        return redirect()->route('countries.index');
    }
}
