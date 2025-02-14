<?php

namespace App\Http\Controllers\User\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Course\CourseRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\MemberCategoryUser;
use Auth;
use Carbon\Carbon;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Material/Course/Index', [
            'courses' => $this->courseRepository->getAllProduction($request),
            'categories' => (new CategoryRepository())->getAllProduction(),
        ]);
    }
}
