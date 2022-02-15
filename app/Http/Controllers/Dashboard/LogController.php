<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Repositories\AccountRepository;
use App\Repositories\ProxyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class LogController extends Controller
{

    /**
     * @var AccountRepository
     */
    private $accountRepository;
    /**
     * @var ProxyRepository
     */
    private $proxyRepository;

    public function __construct(AccountRepository $accountRepository, ProxyRepository $proxyRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->proxyRepository = $proxyRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $req = $request->all();

        $logs = Log::when(isset($req['status']) && $req['status'], function ($q) use ($req) {
            $q->where('status', $req['status']);
        })
            ->when(isset($req['accounts']) && count($req['accounts']), function ($q) use ($req) {
                $q->whereHas('account', function ($subQ) use ($req) {
                    $subQ->whereIn('accounts.id', $req['accounts']);
                });
            })
            ->when(isset($req['proxies']) && count($req['proxies']), function ($q) use ($req) {
                $q->whereHas('proxy', function ($subQ) use ($req) {
                    $subQ->whereIn('proxies.id', $req['proxies']);
                });
            })
            ->when(isset($req['interval']), function ($q) use ($req) {
                $dates = explode(' - ', $req['interval']);
                $q->whereBetween('created_at', $dates);
            })
            ->orderByDesc('created_at')->paginate(20);
        $accounts = $this->accountRepository->getAll();
        $proxies = $this->proxyRepository->getAll();

        $minDate = Log::min('created_at');
        $maxDate = Log::max('created_at');
        $interval = $req['interval'] ?? $minDate . ' - ' . $maxDate;

        return view('dashboard.logs.index', compact('logs', 'accounts', 'req', 'proxies', 'minDate', 'maxDate', 'interval'));
    }


}
