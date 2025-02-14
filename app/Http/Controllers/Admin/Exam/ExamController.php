<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\Exam\GradeRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\Lesson\QuestionRepository;
use App\Http\Requests\Exam\ExamRequest;
use App\Models\MasterData\SubCategory;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroupUser;
use App\Models\Setting\Setting;
use App\Exports\ExamExport;
use Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Repositories\User\MemberCategoryRepository;
use App\Models\Lesson\Question;
use App\Services\CalculateGradeService;
use App\Charts\SectionGradeChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Session;

class ExamController extends Controller
{
    protected $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        Session::put('queryStringExams', $request->getQueryString());

        return inertia('Admin/Exam/Exam/Index', [
            'exams' => $this->examRepository->getAllPaginatedWithParams($request),
            'categories' => (new CategoryRepository())->getAllProduction()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Exam/Exam/Create')->with([
            'categories' => (new CategoryRepository())->all(),
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        try {
            $this->examRepository->create($request);

            return redirect()->route('admin.exams.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$this->examRepository->find($id)) return abort('404', 'uppss....');

        $exam = $this->examRepository->find($id);

        $rankingExams = (new GradeRepository())->getRankingByExam($request, $exam->id, 10);

        return inertia('Admin/Exam/Exam/Show', [
            'exam' => $this->examRepository->find($id),
            'rankingExams' => $rankingExams,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = $this->examRepository->find($id);
        if (!$exam) return abort(404, 'uppss....');

        $exam = Exam::with([
            'subCategories' => function ($query) {
                $query->select('sub_categories.id', 'sub_categories.name');
            },
            'memberCategories' => function ($query) {
                $query->select('member_categories.id', 'member_categories.name');
            }
        ])->find($id);

        $subCategories = $exam->subCategories;
        $memberCategories = $exam->memberCategories;
        
        return inertia('Admin/Exam/Exam/Edit', [
            'exam' => $exam,
            'categories' => (new CategoryRepository())->all(),
            'subCategories' => $subCategories,
            'memberCategorySelected' => $memberCategories, 
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, $id)
    {
        try {
            if(!$this->examRepository->find($id)) return abort('404', 'uppss....');

            $this->examRepository->update($request, $id);

            $queryString = Session::get('queryStringExams');
            return redirect(route('admin.exams.index') . (!empty($queryString) ? '?'.$queryString : ''));

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->examRepository->delete($id);

            return redirect()->route('admin.exams.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeExamStatus(Request $request, $id)
    {
        try {
            if(!$this->examRepository->find($id)) return abort('404', 'uppss....');

            $this->examRepository->find($id)->update(['exam_status' => $request->exam_status]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function deleteExamResult($id)
    {
        try {
            (new GradeRepository())->deleteByExamId($id);

            return redirect()->back();

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function exportPdf($id)
    {
        $exam = (new ExamRepository())->find($id);

        if(!$exam) {
            return abort(404, 'uppss....');
        }

        $setting = Setting::first() ?? [];

        $rankingExams = (new GradeRepository())->getGradeByExam($exam->id);

        $chunkedRankingExams = collect($rankingExams)->chunk(100);

        $pdf = Pdf::loadView('report.pdf.exam', [
            'exam' => $exam,
            'rankingExamChunks' => $chunkedRankingExams,
            'setting' => $setting,
        ]);

        $filename = str_replace(" ", "_", 'Rekapitulasi_Nilai_'.$exam->title.'.pdf');
        return $pdf->stream($filename);
    }


    public function exportExcel($id)
    {
        $exam = (new ExamRepository())->find($id);

        if(!$exam) return abort('404', 'uppss....');

        return Excel::download(new ExamExport($id), 'Rekapitulasi Nilai '.$exam->title.'.xlsx');
    }

    public function deleteExam($id)
    {
        try {
            $grade = (new GradeRepository())->find($id);

            if($grade->exam_group_id) {
                ExamGroupUser::where(['user_id' => $grade->user_id, 'exam_group_id' => $grade->exam_group_id])->update([
                    'grade' => 0,
                    'is_finished' => 0, 
                    'description' => null
                ]);
            }

            $grade->delete();

            return redirect()->back();
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function regenerateBlockToken($id)
    {
        try {
            $this->examRepository->regenerateToken($id);

            return redirect()->back();

        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function regenerateScore($gradeId)
    {
        try {
            $grade = (new GradeRepository())->find($gradeId);

            if(!$grade) {
                return abort(404);
            }

            $gradeAnswers = is_string($grade->answers) ? unserialize($grade->answers) : $grade->answers;

            $newGradeAnswers = [];

            foreach($gradeAnswers as $index => $answers) {

                $newGradeAnswers[$index] = $answers;

                $question = Question::find($answers['question_id']);

                if($question) {
                    $newGradeAnswers[$index]['is_correct'] = $this->arraysEqual($question->answer ?? [], $answers['answer']) ? "Y" : "N";
                    $newGradeAnswers[$index]['correct_point'] = $question->correct_point;
                    $newGradeAnswers[$index]['point_1'] = $question->point_1;
                    $newGradeAnswers[$index]['point_2'] = $question->point_2;
                    $newGradeAnswers[$index]['point_3'] = $question->point_3;
                    $newGradeAnswers[$index]['point_4'] = $question->point_4;
                    $newGradeAnswers[$index]['point_5'] = $question->point_5;
                    $newGradeAnswers[$index]['value_category_id'] = $question->value_category_id;
                }
            }
            
            $grade->update(['answers' => serialize($newGradeAnswers)]);
            (new CalculateGradeService())->calculateGradeCategory($gradeId);

            return redirect()->back();
        } catch (\Throwable $th) {
            return $th;        
        }
    }

    public function userGrade(Request $request, $examId, $id)
    {
        $grade = (new GradeRepository())->find($id);
        $exam = $this->examRepository->find($examId);

        if(!$grade || !$exam) {
            return abort(404);
        }

        if($grade->grade_calculate == 0 || empty($grade->grade_calculate)) {
            (new CalculateGradeService())->calculateGradeCategory($grade->id);
        }
        
        $grade = $grade->refresh();

        $rankingExams = (new GradeRepository())->getRankingByExam($request, $grade->exam_id, 10, 1);

        $chart = new SectionGradeChart(new LarapexChart, $grade);

        return inertia('Admin/Exam/Exam/Grade', [
            'grade' => $grade,
            'exam' => $exam,
            'rankingExams' => $rankingExams,
            'answers' => empty($grade->answers) ? [] : $grade->answers,
            'chart' => $chart->build()
        ]);
    }

    public function answerCorrection(Request $request, $examId, $gradeId)
    {
        $grade = (new gradeRepository())->find($gradeId);
        $exam = (new ExamRepository())->find($examId);

        if (!$grade || !$exam) {
            return abort(404);
        }
        
        $examGroup = (new ExamGroupRepository())->find($grade->exam_group_id) ?? [];

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

        return inertia('Admin/Exam/Exam/AnswerCorrection', [
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

    public function arraysEqual($arr1, $arr2) {
        sort($arr1);
        sort($arr2);
    
        return ($arr1 === $arr2) ? true : false;
    }
}
