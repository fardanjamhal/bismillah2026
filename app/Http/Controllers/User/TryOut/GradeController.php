<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Exam\GradeRepository;
use App\Charts\SectionGradeChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Services\CalculateGradeService;
use App\Repositories\Lesson\QuestionRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;

class GradeController extends Controller
{
    protected $gradeRepository;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/TryOut/Grade/Index', [
            'grades' => $this->gradeRepository->getAllByUserPaginatedWithParams(['user_id' => auth()->user()->id])
        ]);
    }

    public function show(Request $request, $id)
    {
        $grade = $this->gradeRepository->find($id);

        if(!$grade) {
            return abort(404);
        }

        if($grade->grade_calculate == 0 || empty($grade->grade_calculate)) {
            (new CalculateGradeService())->calculateGradeCategory($grade->id);
        }
        
        $grade = $grade->refresh();

        $rankingExams = $this->gradeRepository->getRankingByExam($request, $grade->exam_id, 10, 1);

        $chart = new SectionGradeChart(new LarapexChart, $grade);

        return inertia('User/TryOut/Grade/Show', [
            'grade' => $grade,
            'rankingExams' => $rankingExams,
            'answers' => empty($grade->answers) ? [] : $grade->answers,
            'chart' => $chart->build()
        ]);
    }

    public function question(Request $request, $id)
    {
        $grade = $this->gradeRepository->find($id);

        if (!$grade) {
            return abort(404);
        }

        $exam = (new ExamRepository())->find($grade->exam_id);
        $examGroup = (new ExamGroupRepository())->find($grade->exam_group_id) ?? [];

        if ($exam->show_answer_discussion == 0) {
            return abort(403);
        }

        $questionLists = array_values(unserialize($grade->answers));

        // Filter questionLists berdasarkan value_category_id dan is_correct
        if ($request->valueCategoryId) {
            $valueCategoryId = $request->valueCategoryId;
            $questionLists = array_filter($questionLists, function ($question) use ($valueCategoryId) {
                return $question['value_category_id'] == $valueCategoryId;
            });
        }

        if ($request->isCorrect) {
            $isCorrect = $request->isCorrect == 'Y' ? 'Y' : 'N';
            $questionLists = array_filter($questionLists, function ($question) use ($isCorrect) {
                return $question['is_correct'] == $isCorrect;
            });
        }

        // Mengatur indexPage ke indeks pertama yang valid dalam questionLists
        $questionLists = array_values($questionLists); // Reset the keys
        $firstIndex = array_key_first($questionLists);
        $indexPage = $request->indexPage ?? $firstIndex;

        if (isset($questionLists[$indexPage])) {
            $questionId = $questionLists[$indexPage]['question_id'];
        } else {
            return abort(404);
        }

        $question = '';

        if ($questionId !== null) {
            $question = (new QuestionRepository())->find($questionId);
            if (!$question) {
                return abort(404);
            }
        }

        // Calculate next and previous pages
        $currentIndex = array_search($indexPage, array_keys($questionLists));
        $nextPage = $currentIndex !== false && $currentIndex < count($questionLists) - 1 ? array_keys($questionLists)[$currentIndex + 1] : null;
        $prevPage = $currentIndex !== false && $currentIndex > 0 ? array_keys($questionLists)[$currentIndex - 1] : null;

        return inertia('User/TryOut/Grade/Question', [
            'grade' => $grade,
            'examGroup' => $examGroup,
            'questionLists' => $questionLists,
            'question' => $question,
            'exam' => $exam,
            'indexPage' => $indexPage,
            'valueCategoryId' => $request->valueCategoryId ?? null,
            'isCorrect' => $request->isCorrect ?? null,
            'nextPage' => $nextPage,
            'prevPage' => $prevPage,
        ]);
    }
}
