<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;


class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;


    /**
     * IndexController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return View
     */
    public function index(): View
    {

        $categories = $this->categoryRepository->paginate();

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $categories = $this->categoryRepository->parents();
        return view('dashboard.categories.create', compact('categories'));
    }


    /**
     * @param CategoryRequest $categoryRequest
     * @return RedirectResponse
     */
    public function store(CategoryRequest $categoryRequest): RedirectResponse
    {
        $this->categoryRepository->store($categoryRequest->validated());
        $this->putFlashMessage(true, 'Successfully created');

        return redirect()->route('categories.index');
    }

    /**
     * @param CategoryRequest $categoryRequest
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->categoryRepository->destroy($id);
        $this->putFlashMessage(true, 'Successfully deleted');

        return redirect()->route('categories.index');
    }
}



