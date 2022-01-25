<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProxyRequest;
use App\Repositories\ProxyRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use GuzzleHttp\Client;


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
     * @param int $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function checkLife(int $id): JsonResponse
    {
        $proxy = $this->proxyRepository->getById($id);
        if ($proxy->login && $proxy->password) {
            $config['proxy'] = "{$proxy->type}://{$proxy->login}:{$proxy->password}@{$proxy->ip}:{$proxy->port}";
        } else {
            $config['proxy'] = "{$proxy->type}://{$proxy->ip}:{$proxy->port}";
        }
        $client = new Client($config);

        try {
            $res = $client->get("https://api.ipify.org?format=json");
            return response()->json(json_decode($res->getBody()->getContents()));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()],401);
        }
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

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->proxyRepository->delete($id);
        $this->putFlashMessage(true, 'Successfully');

        return redirect()->back();
    }
}
