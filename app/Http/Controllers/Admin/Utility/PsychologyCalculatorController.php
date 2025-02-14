<?php

namespace App\Http\Controllers\Admin\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\ValueCategoryGroupRepository;
use App\Repositories\MasterData\CategoryRepository;
use Session;

class PsychologyCalculatorController extends Controller
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
     */
    public function index(Request $request)
    {
        Session::put('queryStringPsychologyCalculators', $request->getQueryString());

        return inertia('Admin/Utility/PsychologyCalculator/Index', [
            'categories' => $this->categoryRepository->getCategoryWithValueCategoryGroup()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function calculateScore($categoryId, $valueCategoryGroupId)
    {
        try {
            $category = $this->categoryRepository->find($categoryId);
            $valueCategoryGroup = $this->valueCategoryGroupRepository->getWithDetailValueCategory($valueCategoryGroupId);

            if(!$category) {
                session()->flash('error', 'Data Kategori Peminatan Belum Tersedia.');
                return redirect()->route('admin.psychology-calculators.index');
            }

            if(!$valueCategoryGroup) {
                session()->flash('error', 'Data Kelompok Penilaian Belum Tersedia.');
                return redirect()->route('admin.psychology-calculators.index');
            }

            return inertia('Admin/Utility/PsychologyCalculator/CalculateScore', [
                'category' => $category,
                'valueCategoryGroup' => $valueCategoryGroup,
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
