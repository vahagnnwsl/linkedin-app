<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Jobs\SearchByKeyAndCompany;
use App\Jobs\SyncLastMessagesForOneAccount;
use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Models\Search;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
     * @var CountryRepository
     */
    protected $countryRepository;


    /**
     * SearchController constructor.
     * @param KeyRepository $keyRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(KeyRepository $keyRepository, CompanyRepository $companyRepository, CountryRepository $countryRepository)
    {
        $this->keyRepository = $keyRepository;
        $this->companyRepository = $companyRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        if (Auth::user()->role->name === UserRepository::$ADMIN_ROLE) {
            $searches = Search::orderBy('created_at','desc')->paginate(20);
        }else{
            $searches = Search::where('user_id',Auth::id())->orderBy('created_at','desc')->paginate(20);
        }

        return view('dashboard.search.index', compact('searches'));
    }

    public function destroy($id ) {
        Search::destroy($id);
        $this->putFlashMessage(true, 'Successfully deleted');
        return redirect()->back();
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function linkedin(Request $request)
    {

        $account = Auth::user()->account;

        SearchByKeyAndCompany::dispatch( $account->id,$request->get('country_id'), $request->get('company_id'),$request->get('key_id'));

        $this->putFlashMessage(true, 'Request in process');

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request) {
        $request = $request->all();
        $hash = md5(json_encode($request['params']));
        Search::updateOrCreate([ 'hash'=> $hash, 'user_id' => Auth::id() ],[ 'hash'=> $hash, 'params' => json_decode($request['params']), 'name' => $request['name'], 'user_id' => Auth::id()  ]);
        $this->putFlashMessage(true, 'Successfully saved');

        return redirect()->back();
    }
}
