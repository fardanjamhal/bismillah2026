<?php

namespace App\Repositories\Course;

use App\Models\Course\CourseSection;
use App\Repositories\Contracts\Course\CourseSectionInterface;
use App\Repositories\BaseRepository;

class CourseSectionRepository extends BaseRepository implements CourseSectionInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new CourseSection();
    }

    public function getAllByCoursePaginatedWithParams($courseId, $params, $limit = 20)
    {
        $courseSection = $this->model->query();
        
        if (isset($params->search) && !empty($params->search)) {
            $courseSection->where('title', 'like', '%' . $params->search . '%');
        }
        
        $courseSection = $courseSection
            ->with(['courseDetail' => function ($query) {
                $query->orderBy('order', 'ASC'); // Mengurutkan berdasarkan order dari courseDetail
            }])
            ->where('course_id', $courseId)
            ->orderBy('order', 'ASC') // Mengurutkan berdasarkan order dari courseSection
            ->paginate($limit);
        
        $courseSection->appends([
            'search' => $params->search,
        ]);
        
        return $courseSection;
    }


    public function getByCourseId($courseId)
    {
        return $this->model->where('course_id', $courseId)->orderBy('order', 'ASC')->get();
    }
    
}
