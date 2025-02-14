<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\QuestionTitle;
use App\Repositories\Contracts\Lesson\QuestionTitleInterface;
use App\Repositories\BaseRepository;
use App\Models\Exam\ExamGroup;
use Carbon\Carbon;
use Auth;

class QuestionTitleRepository extends BaseRepository implements QuestionTitleInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new QuestionTitle();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $questionTitles = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $questionTitles->where('name', 'like', '%' . $params->search . '%');
        if(isset($params->category_id) && !empty($params->category_id)) $questionTitles->where('category_id', $params->category_id);
        if(isset($params->lesson_category_id) && !empty($params->lesson_category_id)) $questionTitles->where('lesson_category_id', $params->lesson_category_id);
        if(isset($params->lesson_id) && !empty($params->lesson_id)) $questionTitles->where('lesson_id', $params->lesson_id);

        if(Auth::user()->level == 3) {
            $questionTitles->where('user_id', Auth::user()->id);
        }

        $questionTitles = $questionTitles->with(['category', 'lessonCategory','lesson'])->orderBy('created_at', isset($params->order) && !empty($params->order) ? $params->order : 'DESC')->paginate($limit);

        $questionTitles->appends([
            'search' => $params->search,
            'category_id' => $params->category_id,
            'lesson_category_id' => $params->lesson_category_id,
            'lesson_id' => $params->lesson_id,
            'order' => $params->order,
        ]);

        return $questionTitles;
    }

    public function find($id)
    {
        return $this->model->with(['category', 'lessonCategory', 'valueCategoryGroup', 'lesson', 'user', 'exam', 'exam.examGroup'])->find($id);
    }

    public function getAllByLesson($lessonId)
    {
        $questionTitles = $this->model->query();
        if(Auth::user()->level == 3) {
            $questionTitles->where('user_id', Auth::user()->id);
        }

        return $questionTitles->where('lesson_id', $lessonId)->orderBy('created_at', 'ASC')->get();
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['user_id'] = Auth::user()->id;
        $input['assessment_type'] = $attributes->add_value_category == 0 ? null : $attributes->assessment_type;
        $input['value_category_group_id'] = ($attributes->assessment_type == 1 || $attributes->assessment_type == 2) && $attributes->add_value_category == 1 ? $attributes->value_category_group_id : null;

        return $this->model->create($input);
    }

    public function update($attributes, $id)
    {
        $questionTitle = $this->model->find($id);

        foreach($questionTitle->exam as $exam) {
            if(!empty($exam->exam_group_id)) {
                ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
            }
        }

        $input = $attributes->all();
        $input['assessment_type'] = $attributes->add_value_category == 0 ? null : $attributes->assessment_type;
        $input['minimum_grade'] = $attributes->show_answer == 0 ? 0 : $attributes->minimum_grade;
        $input['value_category_group_id'] = ($attributes->assessment_type == 1 || $attributes->assessment_type == 2) && $attributes->add_value_category == 1 ? $attributes->value_category_group_id : null;

        return $questionTitle->update($input);
    }

    public function delete($id)
    {
        $questionTitle = $this->model->find($id);

        foreach($questionTitle->exam as $exam) {
            if(!empty($exam->exam_group_id)) {
                ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
            }
        }

        return $questionTitle->delete();
    }

    public function totalQuestionTitleByUser()
    {
        return $this->model->where('user_id', Auth::user()->id)->count();
    }
}
