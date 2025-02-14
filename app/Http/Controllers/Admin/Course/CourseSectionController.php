<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseSectionRepository;
use App\Models\Course\Lesson;
use App\Http\Requests\Course\CourseSectionRequest;
use Session;

class CourseSectionController extends Controller
{
    protected $courseSectionRepository;
    protected $courseRepository;

    public function __construct(CourseSectionRepository $courseSectionRepository, courseRepository $courseRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->courseRepository = $courseRepository;
    }

    private function findCourseOrFail($courseId)
    {
        $course = $this->courseRepository->find($courseId);
        if (!$course) {
            abort(404, 'uppss....');
        }
        return $course;
    }

    private function findCourseSectionOrFail($id)
    {
        $courseSection = $this->courseSectionRepository->find($id);
        if (!$courseSection) {
            abort(404, 'uppss....');
        }
        return $courseSection;
    }

    public function index($courseId, Request $request)
    {
        $this->findCourseOrFail($courseId);
        Session::put('queryStringCourseSections', $request->getQueryString());

        return inertia('Admin/Course/CourseSection/Index', [
            'course' => $this->courseRepository->find($courseId),
            'courseSections' => $this->courseSectionRepository->getAllByCoursePaginatedWithParams($courseId, $request)
        ]);
    }

    public function create($courseId)
    {
        $course = $this->findCourseOrFail($courseId);

        return inertia('Admin/Course/CourseSection/Create', [
            'course' => $course,
        ]);
    }

    public function store($courseId, CourseSectionRequest $request)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->courseSectionRepository->create($request->all());

            return redirect()->route('admin.courses.course-details.index', $courseId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $courseId, $courseSectionId)
    {
        $course = $this->courseRepository->find($courseId);
        $courseSection = $this->courseSectionRepository->find($courseSectionId);

        if(!$course || !$courseSection) {
            return abort(404, 'uppss....');
        }

        return inertia('Admin/Course/CourseSection/Show', [
            'course' => $course,
            'courseSection' => $courseSection,
        ]);
    }


    public function edit($courseId, $id)
    {
        $course = $this->findCourseOrFail($courseId);
        $courseSection = $this->findCourseSectionOrFail($id);

        return inertia('Admin/Course/CourseSection/Edit', [
            'course' => $course,
            'courseSection' => $courseSection,
        ]);
    }

    public function update($courseId, CourseSectionRequest $request, $id)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->findCourseSectionOrFail($id);

            return redirect()->route('admin.courses.course-details.index', $courseId);

            return $this->redirectWithQueryString($courseId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($courseId, $id)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->findCourseSectionOrFail($id);

            $this->courseSectionRepository->delete($id);

            return redirect()->route('admin.courses.course-details.index', $courseId);
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

    private function redirectWithQueryString($courseId)
    {
        $queryString = Session::get('queryStringCourseSections');
        return redirect(route('admin.courses.course-details.index', $courseId) . (!empty($queryString) ? '?'.$queryString : ''));
    }
}
