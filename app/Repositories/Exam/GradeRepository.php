<?php

namespace App\Repositories\Exam;

use App\Models\Exam\Grade;
use App\Repositories\Contracts\Exam\GradeInterface;
use App\Repositories\BaseRepository;

class GradeRepository extends BaseRepository implements GradeInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Grade;
    }

    public function getAllByUserPaginatedWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        $exams = $exams->where(['is_finished' => 1, 'user_id' => $params['user_id']])->whereNull('exam_group_id')->with(['category', 'lessonCategory', 'lesson', 'exam'])->orderBy('created_at', 'DESC')->paginate($limit);
        return $exams;
    }

    public function getAllByUserWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        $exams = $exams->where(['is_finished' => 1, 'user_id' => $params['user_id']])->whereNull('exam_group_id')->with(['category', 'lessonCategory', 'lesson', 'exam'])->orderBy('created_at', 'DESC')->get();
        return $exams;
    }

    public function find($id)
    {
        return $this->model->with(['user', 'category', 'lessonCategory', 'lesson', 'exam', 'exam.questionTitle.question', 'exam.questionTitle', 'examGroup'])->find($id);
    }

    public function getRankingByExam($params, $examId, $limit = 10, $isFinished = null)
    {
        $rankings = $this->model->query();

        if (!is_null($isFinished)) {
            $rankings->where('is_finished', $isFinished);
        }

        if (isset($params->search) && !empty($params->search)) {
            $rankings->whereHas('user', function ($query) use ($params) {
                $query->where('code', 'like', '%' . $params->search . '%')
                      ->orWhere('email', 'like', '%' . $params->search . '%')
                      ->orWhere('name', 'like', '%' . $params->search . '%')
                      ->orWhere('username', 'like', '%' . $params->search . '%');
            });
        }

        $rankings = $rankings->where('exam_id', $examId)
            ->with(['user.student', 'user.student.province', 'user.student.city'])
            ->orderBy('grade', 'DESC')
            ->paginate($limit);

        $rankings->appends([
            'search' => $params->search,
        ]);

        return $rankings;
    }

    public function getGradeByExam($examId, $isFinished = null)
    {
        $rankings = $this->model->query();

        if($isFinished) {
            $rankings->where('is_finished', $isFinished);
        }

        return $rankings->where('exam_id', $examId)
                        ->with(['user.student', 'user.student.province', 'user.student.city'])
                        ->orderBy('grade', 'DESC')
                        ->cursor();
    }

    public function deleteByExamId($id)
    {
        return $this->model->where('exam_id', $id)->delete();
    }
}
