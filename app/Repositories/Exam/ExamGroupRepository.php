<?php

namespace App\Repositories\Exam;

use App\Models\Exam\ExamGroup;
use App\Models\Exam\Exam;
use App\Repositories\Contracts\Exam\ExamGroupInterface;
use App\Repositories\BaseRepository;
use Auth;
use App\Models\Setting\Setting;

class ExamGroupRepository extends BaseRepository implements ExamGroupInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ExamGroup();
        $this->setting = Setting::first();
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $examgroups = $this->model;
        if(isset($params->search) && !empty($params->search)) $examgroups->where('title', 'like', '%' . $params->search . '%');
        $examgroups = $examgroups->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $examgroups;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $examGroups = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $examGroups->where('title', 'like', '%' . $params->search . '%');
        if(isset($params->category_id) && !empty($params->category_id)) $examGroups->where('category_id', $params->category_id);
        if(isset($params->lesson_category_id) && !empty($params->lesson_category_id)) $examGroups->where('lesson_category_id', $params->lesson_category_id);

        if(Auth::user()->level == 3) {
            $examGroups->where('user_id', Auth::user()->id);
        }

        $examGroups = $examGroups
            ->withCount(['exam', 'examGroupUser'])
            ->with(['category', 'lessonCategory'])
            ->orderBy('created_at', isset($params->order) && !empty($params->order) ? $params->order : 'DESC')
            ->paginate($limit);

        $examGroups->appends([
                'search' => $params->search,
                'category_id' => $params->category_id,
                'lesson_category_id' => $params->lesson_category_id,
                'order' => $params->order,
            ]);
        return $examGroups;
    }

    public function find($id)
    {
        return $this->model
            ->with([
                'category',
                'subCategories',
                'lessonCategory',
                'user',
                'memberCategories',
                'exam' => function ($query) {
                    $query->orderBy('created_at', 'ASC');
                    $query->with([
                        'questionTitle' => function ($questionTitleQuery) {
                            $questionTitleQuery->withCount('question'); // Hitung jumlah soal di setiap QuestionTitle
                        },
                        'grade' => function ($gradeQuery) {
                            $gradeQuery->where('user_id', Auth::user()->id);
                        }
                    ]);
                }
            ])
            ->find($id);
    }

    public function getByLessonCategory($params, $lessonCategory, $limit = 10)
    {
        $examGroups = $this->model->query();
    
        if (isset($params->search) && !empty($params->search)) {
            $examGroups->where('title', 'like', '%' . $params->search . '%');
        }

        if (isset($params->sub_category_id) && !empty($params->sub_category_id)) {
            $examGroups->whereIn('exam_groups.id', function($query) use ($params) {
                $query->select('exam_group_id')
                    ->from('exam_group_sub_category')
                    ->where('sub_category_id', $params->sub_category_id);
            });
        }

        $examGroups = $examGroups->leftJoin('exam_group_sub_category as egsc', 'exam_groups.id', '=', 'egsc.exam_group_id')
                                ->leftJoin('sub_categories as sc', 'egsc.sub_category_id', '=', 'sc.id')
                                ->with(['category','lessonCategory', 'subCategories', 'memberCategories', 'userAccess'])
                                ->where('exam_groups.lesson_category_id', $lessonCategory)
                                ->whereIn('exam_groups.exam_status', $this->setting->tryout_statuses)
                                ->groupBy('exam_groups.id')  // Group by exam id to avoid duplicates
                                ->orderBy('sc.order', 'ASC')
                                ->orderByRaw('CAST(SUBSTRING_INDEX(exam_groups.title, " ", -1) AS UNSIGNED)')  // Order by the numeric part of the title
                                ->orderBy('exam_groups.title', 'ASC')  // Secondary order by full title
                                ->select('exam_groups.*')
                                ->paginate($limit);
        
        $examGroups->appends([
            'search' => $params->search,
            'sub_category_id' => $params->sub_category_id,
        ]);
        
        return $examGroups;
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['user_id'] = Auth::user()->id;
        $input['duration'] = $attributes->exam_group_type == 1 ? $attributes->duration : null;
        $input['random_question'] = $attributes->exam_group_type == 1 ? $attributes->random_question : null;
        $input['random_answer'] = $attributes->exam_group_type == 1 ? $attributes->random_answer : null;
        $input['show_answer'] = $attributes->exam_group_type == 1 ? $attributes->show_answer : null;
        $input['exam_group_navigation_mode'] = $attributes->exam_group_type == 2 ? $attributes->exam_group_navigation_mode : null;
        $input['show_question_number_navigation'] = $attributes->exam_group_type == 1 ? $attributes->show_question_number_navigation : null;
        $input['show_question_number'] = $attributes->exam_group_type == 1 ? $attributes->show_question_number : null;
        $input['next_question_automatically'] = $attributes->exam_group_type == 1 ? $attributes->next_question_automatically : null;
        $input['show_prev_next_button'] = $attributes->exam_group_type == 1 ? $attributes->show_prev_next_button : null;
        $input['type_option'] = $attributes->exam_group_type == 1 ? $attributes->type_option : null;
        $input['button_type_finish'] = $attributes->exam_group_type == 1 ? $attributes->button_type_finish : null;
        $input['repeat_the_exam'] = $attributes->exam_group_type == 1 ? $attributes->repeat_the_exam : null;
        $input['total_tolerance'] = $attributes->exam_group_type == 1 ? $attributes->total_tolerance : null;
        $input['unblock_token'] = $attributes->exam_group_type == 1 ? $this->generateRandomCode(6) : null;
        $input['minimal_grade'] = $attributes->minimal_grade_type == 2 ? $attributes->minimal_grade : null;
        $input['description_grade_less_than_minimal'] = $attributes->minimal_grade_type != 0 ? $attributes->description_grade_less_than_minimal : null;
        $input['description_grade_more_than_minimal'] = $attributes->minimal_grade_type != 0 ? $attributes->description_grade_more_than_minimal : null;
        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        $examGroup = \App\Models\Exam\ExamGroup::create($input);
        // $examGroup = $this->model->create($input);

        $subCategoryIds = [];
        $memberCategoryIds = [];

        foreach ($attributes->sub_category_ids as $subCategory) {
            $subCategoryIds[] = $subCategory['id'];
        }

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }

        $examGroup->subCategories()->sync($subCategoryIds);
        $examGroup->memberCategories()->sync($memberCategoryIds);

        return $examGroup;
    }

    public function update($attributes, $id)
    {
        $examGroup = $this->model->find($id);

        $input = $attributes->except('_token','_method');
        $input['duration'] = $attributes->exam_group_type == 1 ? $attributes->duration : null;
        $input['random_question'] = $attributes->exam_group_type == 1 ? $attributes->random_question : null;
        $input['random_answer'] = $attributes->exam_group_type == 1 ? $attributes->random_answer : null;
        $input['show_answer'] = $attributes->exam_group_type == 1 ? $attributes->show_answer : null;
        $input['exam_group_navigation_mode'] = $attributes->exam_group_type == 2 ? $attributes->exam_group_navigation_mode : null;
        $input['show_question_number_navigation'] = $attributes->exam_group_type == 1 ? $attributes->show_question_number_navigation : null;
        $input['show_question_number'] = $attributes->exam_group_type == 1 ? $attributes->show_question_number : null;
        $input['next_question_automatically'] = $attributes->exam_group_type == 1 ? $attributes->next_question_automatically : null;
        $input['show_prev_next_button'] = $attributes->exam_group_type == 1 ? $attributes->show_prev_next_button : null;
        $input['type_option'] = $attributes->exam_group_type == 1 ? $attributes->type_option : null;
        $input['button_type_finish'] = $attributes->exam_group_type == 1 ? $attributes->button_type_finish : null;
        $input['repeat_the_exam'] = $attributes->exam_group_type == 1 ? $attributes->repeat_the_exam : null;
        $input['total_tolerance'] = $attributes->exam_group_type == 1 ? $attributes->total_tolerance : null;
        $input['unblock_token'] = $attributes->exam_group_type == 1 ? $this->generateRandomCode(6) : null;
        $input['minimal_grade'] = $attributes->minimal_grade_type == 2 ? $attributes->minimal_grade : null;
        $input['description_grade_less_than_minimal'] = $attributes->minimal_grade_type != 0 ? $attributes->description_grade_less_than_minimal : null;
        $input['description_grade_more_than_minimal'] = $attributes->minimal_grade_type != 0 ? $attributes->description_grade_more_than_minimal : null;
        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        $examGroup->update($input);

        $subCategoryIds = [];
        $memberCategoryIds = [];

        foreach ($attributes->sub_category_ids as $subCategory) {
            $subCategoryIds[] = $subCategory['id'];
        }

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }
        
        $examGroup->subCategories()->sync($subCategoryIds);
        $examGroup->memberCategories()->sync($memberCategoryIds);

        return $examGroup;
    }

    public function regenerateToken($id)
    {
        $examGroup = $this->find($id);
        $examGroup->update(['unblock_token' => $this->generateRandomCode()]);

        return $examGroup;
    }

    function generateRandomCode($length = 8) 
    {
        $bytes = random_bytes($length);
        return strtoupper(substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length));
    }

    public function totalExamGroupByUser()
    {
        return $this->model->where('user_id', Auth::user()->id)->count();
    }

    public function delete($id)
    {
        $examGroup = $this->model->find($id);
        $examGroup->userAccessAll()->delete();
        return $examGroup->delete();
    }


    public function getAllByLessonCategory($lessonCategoryId)
    {
        $query = $this->model->where('lesson_category_id', $lessonCategoryId)
                            ->orderBy('created_at', 'ASC');
        
        if (Auth::user()->level == 3) {
            $query->where('user_id', Auth::user()->id);
        }

        return $query->get();
    }
}
