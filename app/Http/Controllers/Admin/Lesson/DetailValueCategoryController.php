<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\DetailValueCategoryRepository;
use App\Repositories\Lesson\ValueCategoryRepository;
use App\Repositories\Lesson\ValueCategoryGroupRepository;
use App\Http\Requests\Lesson\DetailValueCategoryRequest;
use Session;

class DetailValueCategoryController extends Controller
{
    protected $detailValueCategoryRepository;
    protected $valueCategoryRepository;
    protected $valueCategoryGroupRepository;

    public function __construct(DetailValueCategoryRepository $detailValueCategoryRepository, ValueCategoryRepository $valueCategoryRepository, ValueCategoryGroupRepository $valueCategoryGroupRepository) {
        $this->detailValueCategoryRepository = $detailValueCategoryRepository;
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

    public function index($valueCategoryGroupId, $valueCategoryId, Request $request)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

        if (!$this->valueCategoryRepository->find($valueCategoryId)) {
            return abort('404', 'uppss....');
        }

        Session::put('queryStringDetailValueCategories', $request->getQueryString());

        return inertia('Admin/Lesson/DetailValueCategory/Index', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'valueCategory' => $this->valueCategoryRepository->find($valueCategoryId),
            'detailValueCategories' => $this->detailValueCategoryRepository->getAllByValueCategoryPaginatedWithParams($valueCategoryId, $request)
        ]);
    }

    public function create($valueCategoryGroupId, $valueCategoryId)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

        if (!$this->valueCategoryRepository->find($valueCategoryId)) {
            return abort('404', 'uppss....');
        }

        return inertia('Admin/Lesson/DetailValueCategory/Create', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'valueCategory' => $this->valueCategoryRepository->find($valueCategoryId)
        ]);
    }

    public function store($valueCategoryGroupId, $valueCategoryId, DetailValueCategoryRequest $request)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

            if (!$this->valueCategoryRepository->find($valueCategoryId)) {
                return abort('404', 'uppss....');
            }

            $this->detailValueCategoryRepository->create($request->all());

            return redirect()->route('admin.value-category-groups.value-categories.detail-value-categories.index', [$valueCategoryGroupId, $valueCategoryId]);

        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function edit($valueCategoryGroupId, $valueCategoryId, $id)
    {
        $valueCategoryGroup = $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

        if (!$this->valueCategoryRepository->find($valueCategoryId) || !$this->detailValueCategoryRepository->find($id)) {
            return abort('404', 'uppss....');
        }

        return inertia('Admin/Lesson/DetailValueCategory/Edit', [
            'valueCategoryGroup' => $valueCategoryGroup,
            'valueCategory' => $this->valueCategoryRepository->find($valueCategoryId),
            'detailValueCategory' => $this->detailValueCategoryRepository->find($id),
        ]);
    }

    public function update($valueCategoryGroupId, $valueCategoryId, DetailValueCategoryRequest $request, $id)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

            if (!$this->valueCategoryRepository->find($valueCategoryId) || !$this->detailValueCategoryRepository->find($id)) {
                return abort('404', 'uppss....');
            }

            $this->detailValueCategoryRepository->update($request->all(), $id);

            $queryString = Session::get('queryStringDetailValueCategories');
            return redirect(route('admin.value-category-groups.value-categories.detail-value-categories.index', [$valueCategoryGroupId, $valueCategoryId]) . (!empty($queryString) ? '?' . $queryString : ''));

        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($valueCategoryGroupId, $valueCategoryId, $id)
    {
        try {
            $this->findValueCategoryGroupOrFail($valueCategoryGroupId);

            if (!$this->valueCategoryRepository->find($valueCategoryId) || !$this->detailValueCategoryRepository->find($id)) {
                return abort('404', 'uppss....');
            }

            $this->detailValueCategoryRepository->delete($id);

            return redirect()->route('admin.value-category-groups.value-categories.detail-value-categories.index', [$valueCategoryGroupId, $valueCategoryId]);

        } catch (\Exception $e) {
            return $this->handleException($e, request());
        }
    }

    private function handleException(\Exception $e, $request)
    {
        session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());
        return redirect()->back()->withInput($request->all());
    }
}
