<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\Exam\GradeRepository;
use App\Http\Requests\Exam\ExamGroupRequest;
use App\Models\MasterData\SubCategory;
use App\Models\Exam\ExamGroup;
use App\Models\Exam\Grade;
use App\Models\Exam\ExamGroupUser;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting\Setting;
use Excel;
use App\Exports\ExamGroupExport;
use App\Services\CalculateGradeService;
use App\Repositories\User\MemberCategoryRepository;
use App\Models\Lesson\Question;
use Session;

class ExamGroupController extends Controller
{
    protected $examGroupRepository;

    public function __construct(ExamGroupRepository $examGroupRepository)
    {
        $this->examGroupRepository = $examGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringExamGroups', $request->getQueryString());

        return inertia('Admin/Exam/ExamGroup/Index', [
            'examGroups' => $this->examGroupRepository->getAllPaginatedWithParams($request),
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
        return inertia('Admin/Exam/ExamGroup/Create')->with([
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
    public function store(ExamGroupRequest $request)
    {
        try {
            $this->examGroupRepository->create($request);

            return redirect()->route('admin.exam-groups.index');

        } catch(\Exception $e) {
            return $e;
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
    public function show($id)
    {
        if(!$this->examGroupRepository->find($id)) return abort('404', 'uppss....');

        $exam = $this->examGroupRepository->find($id);

        $rankingExams = (new gradeRepository())->getRankingByExam($exam->id, 10, 1);

        return inertia('Admin/Exam/ExamGroup/Show', [
            'exam' => $this->examGroupRepository->find($id),
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
        if(!$this->examGroupRepository->find($id)) return abort('404', 'uppss....');

        $examGroup = ExamGroup::with([
            'subCategories' => function ($query) {
                $query->select('sub_categories.id', 'sub_categories.name');
            },
            'memberCategories' => function ($query) {
                $query->select('member_categories.id', 'member_categories.name');
            }
        ])->find($id);

        $subCategories = $examGroup->subCategories;
        $memberCategories = $examGroup->memberCategories;

        return inertia('Admin/Exam/ExamGroup/Edit', [
            'examGroup' => $this->examGroupRepository->find($id),
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
    public function update(ExamGroupRequest $request, $id)
    {
        try {
            if(!$this->examGroupRepository->find($id)) return abort('404', 'uppss....');

            $this->examGroupRepository->update($request, $id);

            $queryString = Session::get('queryStringExamGroups');
            return redirect(route('admin.exam-groups.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->examGroupRepository->delete($id);

            return redirect()->back();

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeExamStatus(Request $request, $id)
    {
        try {
            if(!$this->examGroupRepository->find($id)) return abort('404', 'uppss....');

            $this->examGroupRepository->find($id)->update(['exam_status' => $request->exam_status]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function examGroupStudent(Request $request, $id)
    {
        $examGroupUsers = ExamGroupUser::where('exam_group_id', $id);

        if ($request->has('search') && !empty($request->search)) {
            $examGroupUsers->whereHas('user', function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('name', 'like', '%' . $request->search . '%')
                      ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $examGroupUsers = $examGroupUsers->with(['user.student', 'user.student.province', 'user.student.city'])
            ->orderBy('grade', 'DESC')
            ->paginate(10);

        $examGroupUsers->appends([
            'search' => $request->search,
        ]);

        return inertia('Admin/Exam/ExamGroup/Student', [
            'examGroupUsers' => $examGroupUsers,
            'exam_group_id' => $id,
        ]);
    }

    public function examGroupExportPdf($id)
    {
        // Inisialisasi pengambilan data utama dengan relasi
        $examGroup = ExamGroup::with([
            'lessonCategory:id,name',
            'category:id,name',
            'exam:id,exam_group_id,lesson_id',
            'exam.lesson:id,short_name,name',
        ])->find($id);

        if (!$examGroup) {
            return abort(404, 'Data not found.');
        }

        // Pengambilan pengguna yang menyelesaikan ujian secara chunk
        $chunkSize = 100;
        $gradesCollection = collect();
        
        ExamGroupUser::where('exam_group_id', $id)
            // ->where('is_finished', 1)
            ->with([
                'user:id,name',
                'user.student:id,gender,province_id,city_id',
                'user.student.province:id,name',
                'user.student.city:id,name',
            ])
            ->orderBy('grade', 'desc')
            ->chunk($chunkSize, function ($usersChunk) use ($gradesCollection, $examGroup) {
                // Dapatkan exam_ids untuk menghubungkan nilai
                $examIds = $examGroup->exam->pluck('id');

                // Ambil nilai tiap pengguna dalam chunk yang sedang diakses
                $userIds = $usersChunk->pluck('user_id');
                $grades = Grade::whereIn('user_id', $userIds)
                    ->whereIn('exam_id', $examIds)
                    ->get()
                    ->groupBy(function ($grade) {
                        return $grade->user_id . '-' . $grade->exam_id;
                    });
                
                // Gabungkan data grades dengan users untuk satu chunk
                $usersChunk->each(function ($user) use ($grades, $gradesCollection) {
                    $gradesCollection->push([
                        'user' => $user,
                        'grades' => $grades->filter(function ($grade, $key) use ($user) {
                            return str_contains($key, (string)$user->user_id);
                        }),
                    ]);
                });
            });

        // Generate PDF dengan data yang sudah dicombine
        $pdf = Pdf::loadView('report.pdf.exam_group_all', [
            'examGroup' => $examGroup,
            'gradesCollection' => $gradesCollection,
            'setting' => Setting::first() ?? [],
        ]);

        $filename = str_replace(" ", "_", 'Rekapitulasi_Nilai_' . $examGroup->title . '.pdf');
        return $pdf->setPaper('a4', 'landscape')->stream($filename);
    }
    
    public function examGroupExportExcel($id)
    {
        $examGroup = ExamGroup::find($id);
        $filename = str_replace(" ", "_", 'Rekapitulasi_Nilai_' . $examGroup->title . '.xlsx');

        return Excel::download(new ExamGroupExport($id), $filename);
    }

    public function examGroupStudentGrade($examGroupId, $examGroupUserId)
    {
        $examGroupUser = ExamGroupUser::find($examGroupUserId);

        $historyUpdate = ExamGroupUser::with([
            'examGroup.grade' => function ($query) use ($examGroupUser){
                $query->where('user_id', $examGroupUser->user_id)->where('is_finished', 1);
            }
        ])
        ->find($examGroupUserId);

        foreach($historyUpdate->examGroup->grade as $grade) {
            if($grade->grade_calculate == 0) {
                (new CalculateGradeService())->calculateGradeCategory($grade->id);
            }
        }

        $history = ExamGroupUser::with([
            'examGroup.lessonCategory',
            'examGroup.category',
            'examGroup.grade' => function ($query) use ($examGroupUser){
                $query->orderBy('created_at', 'ASC');
                $query->where('user_id', $examGroupUser->user_id)
                        ->with([
                        'lesson',
                        'exam.questionTitle',
                    ]);
            },
        ])
        ->find($examGroupUserId);

        return inertia('Admin/Exam/ExamGroup/HistoryDetail', [
            'history' => $history,
        ]);
    }

    public function regenerateScore($examGroupId, $examGroupUserId)
    {
        try {
            $examGroupUser = ExamGroupUser::find($examGroupUserId);

            $historyUpdate = ExamGroupUser::with([
                'examGroup.grade' => function ($query) use ($examGroupUser){
                    $query->where('user_id', $examGroupUser->user_id)->where('is_finished', 1);
                }
            ])
            ->find($examGroupUserId);

            foreach($historyUpdate->examGroup->grade as $grade) {

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

                (new CalculateGradeService())->calculateGradeCategory($grade->id);
            }

            session()->flash('success', 'Hitung Ulang Nilai Berhasil.');
            return redirect()->back();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            return redirect()->back();
        }

    }

    public function arraysEqual($arr1, $arr2) {
        sort($arr1);
        sort($arr2);
    
        return ($arr1 === $arr2) ? true : false;
    }

    public function regenerateAllScore($examGroupId)
    {
        try {
            ExamGroupUser::where('exam_group_id', $examGroupId)->chunk(100, function ($examGroupUsers) {
                foreach($examGroupUsers as $examGroupUser) {
                    $grades = ExamGroupUser::with([
                        'examGroup.grade' => function ($query) use ($examGroupUser) {
                            $query->where('user_id', $examGroupUser->user_id)->where('is_finished', 1);
                        }
                    ])->find($examGroupUser->id)->examGroup->grade;

                    foreach($grades as $grade) {

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
        
                        (new CalculateGradeService())->calculateGradeCategory($grade->id);
                    }
                }
            });

            session()->flash('success', 'Hitung Ulang Nilai Berhasil.');
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());
            return redirect()->back();
        }
}

    

    public function examGroupStudentExportPdf($examGroupId, $examGroupUserId)
    {
        $examGroupUser = ExamGroupUser::find($examGroupUserId);

        $history = ExamGroupUser::with([
            'examGroup.lessonCategory',
            'examGroup.category',
            'examGroup.grade' => function ($query) use($examGroupUser) {
                $query->orderBy('created_at', 'ASC');
                $query->where('user_id', $examGroupUser->user_id)
                    ->with([
                        'lesson',
                        'exam.questionTitle',
                    ]);
            },
        ])
        ->find($examGroupUserId);

        $gradeCount = ExamGroup::withCount('grade')->find($history->exam_group_id);

        $pdf = Pdf::loadView('report.pdf.exam_group', [
            'setting' => Setting::first() ?? [],
            'history' => $history,
            'gradeCount' => $gradeCount->grade_count ?? 0
        ]);

        $filename = str_replace(" ", "_", $history->examGroup->lessonCategory->name.'_'.$history->user->name.'.pdf');
        return $pdf->stream($filename);
    }

    public function examGroupUserDestroy($id)
    {
        $examGroupUser = ExamGroupUser::find($id);
        Grade::where(['exam_group_id' => $examGroupUser->exam_group_id, 'user_id' => $examGroupUser->user_id])->delete();
        ExamGroupUser::find($id)->delete();

        return redirect()->back();
    }

    public function deleteExamGroupResult($id)
    {
        Grade::where('exam_group_id', $id)->delete();
        ExamGroupUser::where('exam_group_id', $id)->delete();

        return redirect()->back();
    }

    public function regenerateBlockToken($id)
    {
        try {
            $this->examGroupRepository->regenerateToken($id);

            return redirect()->back();

        } catch (\Throwable $th) {
            return $th;
        }
    }
    
}
