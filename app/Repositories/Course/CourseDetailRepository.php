<?php

namespace App\Repositories\Course;

use App\Models\Course\CourseDetail;
use App\Repositories\Contracts\Course\CourseDetailInterface;
use App\Repositories\BaseRepository;

class CourseDetailRepository extends BaseRepository implements CourseDetailInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new CourseDetail();
    }

    public function getAllByCoursePaginatedWithParams($courseId, $params, $limit = 10)
    {
        $courseDetail = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $courseDetail->where('title', 'like', '%' . $params->search . '%');
        $courseDetail = $courseDetail->where('course_id', $courseId)->orderBy('order', 'ASC')->paginate($limit);
       
        $courseDetail->appends([
            'search' => $params->search,
        ]);
        
        return $courseDetail;
    }
    
}
