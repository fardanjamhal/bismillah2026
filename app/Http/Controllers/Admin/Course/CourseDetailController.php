<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseDetailRepository;
use App\Repositories\Course\CourseSectionRepository;
use App\Models\Course\Lesson;
use App\Http\Requests\Course\CourseDetailRequest;
use Session;

class CourseDetailController extends Controller
{
    protected $courseDetailRepository;
    protected $courseRepository;
    protected $courseSectionRepository;

    public function __construct(CourseSectionRepository $courseSectionRepository, CourseDetailRepository $courseDetailRepository, courseRepository $courseRepository)
    {
        $this->courseDetailRepository = $courseDetailRepository;
        $this->courseRepository = $courseRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    private function findCourseOrFail($courseId)
    {
        $course = $this->courseRepository->find($courseId);
        if (!$course) {
            abort(404, 'uppss....');
        }
        return $course;
    }

    private function findCourseDetailOrFail($id)
    {
        $courseDetail = $this->courseDetailRepository->find($id);
        if (!$courseDetail) {
            abort(404, 'uppss....');
        }
        return $courseDetail;
    }

    public function index($courseId, Request $request)
    {
        $this->findCourseOrFail($courseId);
        Session::put('queryStringCourseDetails', $request->getQueryString());

        // return $this->courseSectionRepository->getAllByCoursePaginatedWithParams($courseId, $request);

        return inertia('Admin/Course/CourseDetail/Index', [
            'course' => $this->courseRepository->find($courseId),
            'courseSections' => $this->courseSectionRepository->getAllByCoursePaginatedWithParams($courseId, $request),
        ]);
    }

    public function create($courseId)
    {
        $course = $this->findCourseOrFail($courseId);
        $courseSections = $this->courseSectionRepository->getByCourseId($courseId);

        return inertia('Admin/Course/CourseDetail/Create', [
            'course' => $course,
            'courseSections' => $courseSections,
        ]);
    }

    public function store($courseId, CourseDetailRequest $request)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->courseDetailRepository->create($request->all());

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
    public function show(Request $request, $courseId, $courseDetailId)
    {
        $course = $this->courseRepository->find($courseId);
        $courseDetail = $this->courseDetailRepository->find($courseDetailId);

        if(!$course || !$courseDetail) {
            return abort(404, 'uppss....');
        }

        return inertia('Admin/Course/CourseDetail/Show', [
            'course' => $course,
            'courseDetail' => $courseDetail,
        ]);
    }


    public function edit($courseId, $id)
    {
        $course = $this->findCourseOrFail($courseId);
        $courseDetail = $this->findCourseDetailOrFail($id);
        $courseSections = $this->courseSectionRepository->getByCourseId($courseId);

        return inertia('Admin/Course/CourseDetail/Edit', [
            'course' => $course,
            'courseDetail' => $courseDetail,
            'courseSections' => $courseSections,
        ]);
    }

    public function update($courseId, CourseDetailRequest $request, $id)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->findCourseDetailOrFail($id);

            $this->courseDetailRepository->update($request->all(), $id);

            return $this->redirectWithQueryString($courseId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($courseId, $id)
    {
        try {
            $this->findCourseOrFail($courseId);
            $this->findCourseDetailOrFail($id);

            $this->courseDetailRepository->delete($id);

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
        $queryString = Session::get('queryStringCourseDetails');
        return redirect(route('admin.courses.course-details.index', $courseId) . (!empty($queryString) ? '?'.$queryString : ''));
    }
}
