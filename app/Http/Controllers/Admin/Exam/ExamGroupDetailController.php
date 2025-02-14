<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\Exam\GradeRepository;
use App\Http\Requests\Exam\ExamGroupDetailRequest;
use Ramsey\Uuid\Uuid as Generator;
use App\Models\Exam\Exam;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;
use App\Models\Setting\Setting;
use App\Exports\ExamExport;
use App\Repositories\User\MemberCategoryRepository;
use App\Models\Lesson\Question;
use App\Services\CalculateGradeService;
use App\Charts\SectionGradeChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Session;

class ExamGroupDetailController extends Controller
{
    protected $ExamGroupRepository;

    public function __construct(ExamGroupRepository $examGroupRepository)
    {
        $this->examGroupRepository = $examGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($examGroupId, Request $request)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        return inertia('Admin/Exam/ExamGroupDetail/Index', [
            'exams' => (new ExamRepository())->getByExamGroupPaginatedWithParams($request, $examGroupId),
            'examGroup' => $this->examGroupRepository->find($examGroupId)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($examGroupId)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        $examGroup = $this->examGroupRepository->find($examGroupId);

        return inertia('Admin/Exam/ExamGroupDetail/Create', [
            'examGroup' => $examGroup,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($examGroupId, ExamGroupDetailRequest $request)
    {
        try {
            if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

            (new ExamRepository())->create($request);

            return redirect()->route('admin.exam-groups.exam-group-details.index', $examGroupId);

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
    public function show(Request $request, $examGroupId, $id)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        if(!(new ExamRepository())->find($id)) return abort('404', 'uppss....');

        $exam = (new ExamRepository())->find($id);

        $rankingExams = (new GradeRepository())->getRankingByExam($request, $exam->id, 10);

        return inertia('Admin/Exam/ExamGroupDetail/Show', [
            'exam' => (new ExamRepository())->find($id),
            'rankingExams' => $rankingExams,
            'examGroup' => $this->examGroupRepository->find($examGroupId),
        ]);
    }

    public function userGrade(Request $request, $examGroupId, $examId, $gradeId)
    {
        $grade = (new GradeRepository())->find($gradeId);
        $exam = (new ExamRepository())->find($examId);

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

    public function exportPdf($examGroupId, $id)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        $exam = (new ExamRepository())->find($id);
        
        if(!$exam) return abort('404', 'uppss....');

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

    public function exportExcel($examGroupId, $id)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        if(!(new ExamRepository())->find($id)) return abort('404', 'uppss....');

        $exam = (new ExamRepository())->find($id);

        return Excel::download(new ExamExport($id), 'Rekapitulasi Nilai '.$exam->title.'.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($examGroupId, $id)
    {
        if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

        if(!(new ExamRepository())->find($id)) return abort('404', 'uppss....');

        $examGroup = $this->examGroupRepository->find($examGroupId);

        return inertia('Admin/Exam/ExamGroupDetail/Edit', [
            'examGroup' => $examGroup,
            'exam' => (new ExamRepository())->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($examGroupId, ExamGroupDetailRequest $request, $id)
    {
        try {
            if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

            if(!(new ExamRepository())->find($id)) return abort('404', 'uppss....');

            (new ExamRepository())->update($request, $id);

            return redirect()->route('admin.exam-groups.exam-group-details.index', $examGroupId);

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
    public function destroy($examGroupId, $id)
    {
        try {
            if(!$this->examGroupRepository->find($examGroupId)) return abort('404', 'uppss....');

            if(!(new ExamRepository())->find($id)) return abort('404', 'uppss....');

            (new ExamRepository())->delete($id);

            return redirect()->route('admin.exam-groups.exam-group-details.index', $examGroupId);

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back();
        }
    }
}
