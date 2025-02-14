<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\ValueCategoryGroupRepository;
use App\Repositories\Lesson\ValueCategoryRepository;
use App\Models\Lesson\Lesson;
use App\Http\Requests\Lesson\ValueCategoryRequest;
use Session;

class ValueCategoryController extends Controller
{
    protected $valueCategoryRepository;
    protected $valueCategoryGroupRepository;

    public function __construct(ValueCategoryRepository $valueCategoryRepository, ValueCategoryGroupRepository $valueCategoryGroupRepository)
    {
        $this->valueCategoryRepository = $valueCategoryRepository;
        $this->valueCategoryGroupRepository = $valueCategoryGroupRepository;
    }

    private function findValueCategoryGroupOrFail($valueCategoryGroupId)
    {
        $valueCategoryGroup = $this->valueCategoryGroupRepository->find($valueCategoryGroupId);
        if (!$valueCategoryGroup) {
            abort(404, 'uppss....');
        }
        return $valueCategoryGroup;
    }

    private function findValueCategoryOrFail($id)
    {
        $valueCategory = $this->valueCategoryRepository->find($id);
        if (!$valueCategory) {
            abort(404, 'uppss....');
        }
        return $valueCategory;
    }

    private function getLessons($categoryId)
    {
        return Lesson::whereHas('lessonCategory', function($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->with(['lessonCategory', 'lessonCategory.category'])
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    public function index($valueCategoryGroupId, Request $request)
    {
        $this->findValueCategoryGroupOrFail($valueCategoryGroupId);
        Session::put('queryStringValueCategories', $request->getQueryString());

        return inertia('Admin/Lesson/ValueCategory/Index', [
            'valueCategoryGroup' => $this->valueCategoryGroupRepository->find($valueCategoryGroupId),
            'valueCategories' => $this->valueCategoryRepository->getAllByValueCategoryGroupPaginatedWithParams($valueCategoryGroupId, $request)
        ]);
    }

    public function create($valueCategoryGroupId)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

        return inertia('Admin/Lesson/ValueCategory/Create', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'lessons' => $this->getLessons($valueCategoryGroup->category_id),
        ]);
    }

    public function store($valueCategoryGroupId, ValueCategoryRequest $request)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);
            $this->valueCategoryRepository->create($request);

            return redirect()->route('admin.value-category-groups.value-categories.index', $valueCategoryGroupId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function edit($valueCategoryGroupId, $id)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($valueCategoryGroupId);
        $valueCategory = $this->findValueCategoryOrFail($id);

        return inertia('Admin/Lesson/ValueCategory/Edit', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'valueCategory' => $valueCategory,
            'lessons' => $this->getLessons($valueCategoryGroup->category_id),
        ]);
    }

    public function update($valueCategoryGroupId, ValueCategoryRequest $request, $id)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);
            $this->findValueCategoryOrFail($id);

            $this->valueCategoryRepository->update($request, $id);

            return $this->redirectWithQueryString($valueCategoryGroupId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($valueCategoryGroupId, $id)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);
            $this->findValueCategoryOrFail($id);

            $this->valueCategoryRepository->delete($id);

            return redirect()->route('admin.value-category-groups.value-categories.index', $valueCategoryGroupId);
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            return redirect()->back();
        }
    }

    private function handleException(\Exception $e, $request)
    {
        session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
        return redirect()->back()->withInput($request->all());
    }

    private function redirectWithQueryString($valueCategoryGroupId)
    {
        $queryString = Session::get('queryStringValueCategories');
        return redirect(route('admin.value-category-groups.value-categories.index', $valueCategoryGroupId) . (!empty($queryString) ? '?'.$queryString : ''));
    }
}
