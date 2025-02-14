<?php

namespace App\Repositories\Exam;

use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Repositories\Contracts\Exam\ExamInterface;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Auth;
use App\Models\Setting\Setting;

class ExamRepository extends BaseRepository implements ExamInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Exam();
        $this->setting = Setting::first();
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        $exams = $exams->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $exams;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $exams = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        if(isset($params->category_id) && !empty($params->category_id)) $exams->where('category_id', $params->category_id);
        if(isset($params->lesson_category_id) && !empty($params->lesson_category_id)) $exams->where('lesson_category_id', $params->lesson_category_id);
        if(isset($params->lesson_id) && !empty($params->lesson_id)) $exams->where('lesson_id', $params->lesson_id);

        if(Auth::user()->level == 3) {
            $exams->where('user_id', Auth::user()->id);
        }

        $exams = $exams->whereNull('exam_group_id')
            ->withCount('grade')
            ->with(['category', 'lessonCategory', 'lesson', 'questionTitle'])
            ->orderBy('created_at', isset($params->order) && !empty($params->order) ? $params->order : 'DESC')
            ->paginate($limit);
        
        $exams->appends([
            'search' => $params->search,
            'category_id' => $params->category_id,
            'lesson_category_id' => $params->lesson_category_id,
            'lesson_id' => $params->lesson_id,
            'order' => $params->order,
        ]);

        return $exams;
    }

    public function getAllByLessonPaginatedWithParams($params, $lessonId, $limit = 10)
    {
        $exams = $this->model->query();

        if (isset($params->search) && !empty($params->search)) {
            $exams->where('title', 'like', '%' . $params->search . '%');
        }

        if (isset($params->sub_category_id) && !empty($params->sub_category_id)) {
            $exams->whereIn('exams.id', function($query) use ($params) {
                $query->select('exam_id')
                    ->from('exam_sub_category')
                    ->where('sub_category_id', $params->sub_category_id);
            });
        }

        $exams = $exams->leftJoin('exam_sub_category as egsc', 'exams.id', '=', 'egsc.exam_id')
            ->leftJoin('sub_categories as sc', 'egsc.sub_category_id', '=', 'sc.id')
            ->with([
                'category', 
                'lessonCategory', 
                'lesson', 
                'questionTitle', 
                'subCategories', 
                'memberCategories', 
                'userAccess'
            ])
            ->where('lesson_id', $lessonId)
            ->whereNull('exam_group_id')
            ->groupBy('exams.id')
            ->orderBy('sc.order', 'ASC')
            ->orderByRaw("
                CAST(
                    REGEXP_REPLACE(exams.title, '[^0-9]+', '') AS UNSIGNED
                )
            ")
            ->whereIn('exams.exam_status', $this->setting->practice_question_statuses)
            ->orderBy('exams.title', 'ASC')
            ->select('exams.*')
            ->paginate($limit);

                
        $exams->appends([
            'search' => $params->search,
            'sub_category_id' => $params->sub_category_id,
        ]);

        return $exams;
    }

    public function getByExamGroupPaginatedWithParams($params, $examGroupId, $limit = 10)
    {
        $exams = $this->model;
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        $exams = $exams->where('exam_group_id', $examGroupId)->withCount('gradeFinished')->withCount('grade')->with(['category', 'lessonCategory', 'lesson', 'questionTitle'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $exams;
    }

    public function find($id)
    {
        return $this->model->with([
                'category',
                'lessonCategory',
                'lesson',
                'questionTitle' => function ($questionTitleQuery) {
                    $questionTitleQuery->withCount('question');
                },
                'subCategories',
                'user',
                'memberCategories'
            ])
            ->find($id);
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['user_id'] = Auth::user()->id;
        $input['unblock_token'] = $attributes->total_tolerance == null ? null : $this->generateRandomCode(6); 
        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        $input['number_of_questions'] = $attributes->question_selection_mode == 1 ? null : $attributes->number_of_questions;

        $exam = $this->model->create($input);

        if(!$attributes->exam_group_id) {
            $subCategoryIds = [];
            $memberCategoryIds = [];

            foreach ($attributes->sub_category_ids as $subCategory) {
                $subCategoryIds[] = $subCategory['id'];
            }

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $exam->subCategories()->sync($subCategoryIds);
            $exam->memberCategories()->sync($memberCategoryIds);
        }

        if(!empty($exam->exam_group_id)) {
            ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
        }

        return $exam;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $exam = $this->find($id);

        $input['unblock_token'] = $attributes->total_tolerance == null ? null : $this->generateRandomCode(6); 
        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        $input['number_of_questions'] = $attributes->question_selection_mode == 1 ? null : $attributes->number_of_questions;
        
        $exam->update($input);

        if(!$attributes->exam_group_id) {
            $subCategoryIds = [];
            $memberCategoryIds = [];

            foreach ($attributes->sub_category_ids as $subCategory) {
                $subCategoryIds[] = $subCategory['id'];
            }

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $exam->subCategories()->sync($subCategoryIds);
            $exam->memberCategories()->sync($memberCategoryIds);
        }

        if(!empty($exam->exam_group_id)) {
            ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
        }

        return $exam;
    }

    public function delete($id)
    {
        $exam = $this->model->find($id);

        if(!empty($exam->exam_group_id)) {
            ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
        }

        $exam->userAccessAll()->delete();
        return $exam->delete();
    }

    public function regenerateToken($id)
    {
        $exam = $this->find($id);
        $exam->update(['unblock_token' => $this->generateRandomCode()]);

        return $exam;
    }

    function generateRandomCode($length = 8) 
    {
        $bytes = random_bytes($length);
        return strtoupper(substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length));
    }

    public function totalExamByUser()
    {
        return $this->model->where('user_id', Auth::user()->id)->count();
    }

    public function getAllByLesson($lessonId)
    {
        $query = $this->model->where('lesson_id', $lessonId)
                            ->whereNull('exam_group_id')
                            ->orderBy('created_at', 'ASC');
        
        if (Auth::user()->level == 3) {
            $query->where('user_id', Auth::user()->id);
        }

        return $query->get();
    }
}
