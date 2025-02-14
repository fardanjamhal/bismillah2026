<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Repositories\Course\CourseRepository;
use App\Http\Requests\Course\CourseRequest;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\User\MemberCategoryRepository;
use Session;

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
        Session::put('queryStringCourses', $request->getQueryString());

        return inertia('Admin/Course/Course/Index', [
            'courses' => $this->courseRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Course/Course/Create', [
            'categories' => (new CategoryRepository())->all(),
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        try {
            $this->courseRepository->create($request);

            return redirect()->route('admin.courses.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
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
        $course = Course::with([
            'memberCategories' => function ($query) {
                $query->select('member_categories.id', 'member_categories.name');
            }
        ])->find($id);

        $memberCategorySelected = $course->memberCategories;
        $courseGoals = $course->courseGoal()->pluck('description');

        if(!$course) return abort('404', 'uppss....');

        return inertia('Admin/Course/Course/Edit', [
            'course' => $course,
            'categories' => (new CategoryRepository())->all(),
            'courseGoals' => $courseGoals,
            'memberCategorySelected' => $memberCategorySelected,
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseRequest $request, $id)
    {
        try {
            if(!$this->courseRepository->find($id)) return abort('404', 'uppss....');

            $this->courseRepository->update($request, $id);

            $queryString = Session::get('queryStringCourses');
            return redirect(route('admin.courses.index') . (!empty($queryString) ? '?'.$queryString : ''));

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
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
            $this->courseRepository->delete($id);
            
            return redirect()->route('admin.courses.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $course = $this->courseRepository->find($id);

            if(!$course) return abort('404', 'uppss....');

            $this->courseRepository->find($id)->update(['status' => $request->status]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
