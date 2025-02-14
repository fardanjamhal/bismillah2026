<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\ValueCategoryGroupRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Http\Requests\Lesson\ValueCategoryGroupRequest;
use Session;

class ValueCategoryGroupController extends Controller
{
    protected $valueCategoryGroupRepository;
    protected $categoryRepository;

    public function __construct(ValueCategoryGroupRepository $valueCategoryGroupRepository, CategoryRepository $categoryRepository)
    {
        $this->valueCategoryGroupRepository = $valueCategoryGroupRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringValueCategoryGroups', $request->getQueryString());

        return inertia('Admin/Lesson/ValueCategoryGroup/Index', [
            'valueCategoryGroups' => $this->valueCategoryGroupRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Lesson/ValueCategoryGroup/Create', [
            'categories' => $this->categoryRepository->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ValueCategoryGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValueCategoryGroupRequest $request)
    {
        try {
            $this->valueCategoryGroupRepository->create($request->validated());

            return redirect()->route('admin.value-category-groups.index');

        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($id);

        return inertia('Admin/Lesson/ValueCategoryGroup/Edit', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'categories' => $this->categoryRepository->all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValueCategoryGroupRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValueCategoryGroupRequest $request, $id)
    {
        try {
            $this->findValueCategoryGroupOrFail($id);
            $this->valueCategoryGroupRepository->update($request->validated(), $id);

            return $this->redirectWithQueryString('admin.value-category-groups.index');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->valueCategoryGroupRepository->delete($id);

            return redirect()->route('admin.value-category-groups.index');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Find ValueCategoryGroup by ID or abort with 404.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function findValueCategoryGroupOrFail($id)
    {
        $valueCategoryGroup = $this->valueCategoryGroupRepository->find($id);
        if (!$valueCategoryGroup) {
            abort(404, 'uppss....');
        }
        return $valueCategoryGroup;
    }

    /**
     * Handle exception and redirect back with an error message.
     *
     * @param  \Exception  $e
     * @param  Request|null  $request
     * @return \Illuminate\Http\Response
     */
    private function handleException(\Exception $e, Request $request = null)
    {
        session()->flash('error', $e->getMessage() . ' in file: ' . $e->getFile() . ' line: ' . $e->getLine());

        return redirect()->back()->withInput($request ? $request->all() : []);
    }

    /**
     * Redirect with query string.
     *
     * @param  string  $routeName
     * @return \Illuminate\Http\Response
     */
    private function redirectWithQueryString($routeName)
    {
        $queryString = Session::get('queryStringValueCategoryGroups');
        return redirect(route($routeName) . (!empty($queryString) ? '?' . $queryString : ''));
    }
}