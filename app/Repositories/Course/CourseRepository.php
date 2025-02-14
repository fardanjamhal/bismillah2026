<?php

namespace App\Repositories\Course;

use App\Models\Course\Course;
use App\Repositories\Contracts\Course\CourseInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;

class CourseRepository extends BaseRepository implements CourseInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/courses';

    public function __construct()
    {
        $this->model = new Course();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $courses = $this->model->query();

        if(isset($params->search) && !empty($params->search)) {
            $courses->where('title', 'like', '%' . $params->search . '%');
        }

        $courses = $courses->with('category', 'memberCategories')
                        ->withCount('courseDetail')
                        ->join('categories', 'courses.category_id', '=', 'categories.id')
                        ->orderBy('categories.order', 'ASC')
                        ->orderBy('courses.order', 'ASC')
                        ->paginate($limit);

        $courses->appends([
            'search' => $params->search,
        ]);

        return $courses;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        
        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            $input['thumbnail'] = $filename;
        }

        $course = $this->model->create($input);

        $memberCategoryIds = [];

        foreach ($attributes->goals as $goal) {
            $course->courseGoal()->create(['description' => $goal]);
        }

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }
        
        $course->memberCategories()->sync($memberCategoryIds);

        return $course;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $course = $this->find($id);

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            (new UploadService())->deleteFile($course->thumbnail, $this->image_path);
            $input['thumbnail'] = $filename;
        } else {
            $input['thumbnail'] = $course->thumbnail;
        }

        $memberCategories = [];

        $course->update($input);

        $memberCategoryIds = [];

        $course->courseGoal()->delete();
        
        foreach ($attributes->goals as $goal) {
            $course->courseGoal()->create(['description' => $goal]);
        }

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }
        
        $course->memberCategories()->sync($memberCategoryIds);

        return $course;
    }

    public function delete($id)
    {
        $course = $this->model->find($id);
        (new UploadService())->deleteFile($course->thumbnail, $this->image_path);
        return $course->delete();
    }

    public function getAllProduction($params, $limit = 15)
    {
        $courses = $this->model->query();

        if(isset($params->search) && !empty($params->search)) {
            $courses->where('title', 'like', '%' . $params->search . '%');
        }

        if(isset($params->category_id) && !empty($params->category_id)) {
            $courses->where('category_id', $params->category_id);
        }

        $courses = $courses->with('category', 'memberCategories')
                        ->withCount('courseDetail')
                        ->join('categories', 'courses.category_id', '=', 'categories.id')
                        ->orderBy('categories.order', 'ASC')
                        ->orderBy('courses.order', 'ASC')
                        ->paginate($limit);

        $courses->appends([
            'search' => $params->search,
        ]);

        return $courses;
    }
}
