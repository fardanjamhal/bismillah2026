<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Exam\ExamRepository;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MasterData\SubCategoryRepository;
use App\Models\Exam\Grade;
use App\Models\Lesson\Question;
use App\Models\MemberCategoryUser;
use App\Models\Lesson\QuestionTitle;
use App\Repositories\Lesson\LessonRepository;
use App\Models\Lesson\{DetailValueCategory, ValueCategory};
use Carbon\Carbon;
use App\Models\Exam\ExamGroup;
use App\Models\Exam\Exam;
use Auth;
use App\Models\Transaction\Transaction;
use App\Models\Exam\ExamGroupUser;
use App\Models\Setting\Setting;
use File;

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
    public function index(Request $request, $categoryId, $lessonCategoryId, $lessonId)
    {
        return inertia('User/TryOut/Exam/Index', [
            'exams' => $this->examRepository->getAllByLessonPaginatedWithParams($request, $lessonId, 20),
            'categoryId' => $categoryId,
            'lessonCategoryId' => $lessonCategoryId,
            'lessonId' => $lessonId,
            'subCategories' => (new SubCategoryRepository())->getByCategoryId($categoryId),
            'userMemberCategories' => MemberCategoryUser::where('category_id', $categoryId)->where('user_id', Auth::user()->id)->where('expired_date', '>', Carbon::now())->get()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId, $lessonCategoryId, $lessonId, $id)
    {
        $grade = Grade::where('exam_id', $id)->where('user_id', auth()->user()->id)->first();

        return inertia('User/TryOut/Exam/Show', [
            'exam' => $this->examRepository->find($id),
            'grade' => $grade
        ]);
    }

    function checkMemberCategories($data, $categories) 
    {
        if ($categories && count($categories) > 0) {
            $memberCategoryIds = $data->toArray(); 
            $memberCategoryIds = $memberCategoryIds; 

            return count(array_intersect($memberCategoryIds, $categories)) > 0;
        } else {
            return true;
        }
    }

    public function examStart($id, Request $request)
    {
        DB::beginTransaction();

        try {                 
            $exam = Exam::find($id);

            $setting = Setting::first();

            if($exam->exam_status != 'active') {
                session()->flash('error', 'Anda tidak dapat memulai '.($exam->exam_group_id ? 'Tryout' : 'latihan soal').' karena status '.($exam->exam_group_id ? 'Tryout' : 'latihan soal '). '<b>'.strtoupper($exam->exam_status).'</b>');
                
                if($exam->exam_group_id) {
                    return redirect()->route('user.exam_group.show', [
                        'id' => $exam->category_id, 
                        'lessonCategoryId' => $exam->lesson_category_id, 
                        'examGroupId' => $exam->exam_group_id, 
                        'exam' => $exam->id
                    ]);
                } else {
                    return redirect()->route('user.categories.lesson-categories.lessons.exams.show', [
                        'category' => $exam->category_id, 
                        'lesson_category' => $exam->lesson_category_id, 
                        'lesson' => $exam->lesson_id, 
                        'exam' => $exam->id
                    ]);
                }
            }

            $checkExam = empty($exam->exam_group_id) ? $exam : ExamGroup::find($exam->exam_group_id);
            $salesTypeKey = $exam->exam_group_id ? 'tryout' : 'practice_question';
            $salesType = $setting->{$salesTypeKey . '_sales_type'};
            $enable = $setting->{'enable_'. $salesTypeKey.'_sales'};

            if(Auth::user()->member_type == 2 && $enable == 1 && $checkExam->price_type == 2) {
                $checkAccess = false;

                $checkMemberCategories = $this->checkMemberCategories(
                    MemberCategoryUser::where([
                        'category_id' => $exam->category_id, 
                        'user_id' => Auth::user()->id
                    ])
                    ->where('expired_date', '>', Carbon::now())
                    ->pluck('member_category_id'), 
                    $checkExam->memberCategories->pluck('id')->toArray() // Mengonversi koleksi menjadi array
                );
              
                if ($salesType == 1 && count($checkExam->userAccess) > 0) {
                    $checkAccess = true;
                }

                if ($salesType == 2 && $checkMemberCategories == true) {
                    $checkAccess = true;
                }

                if ($salesType == 3 && (count($checkExam->userAccess) > 0 || $checkMemberCategories == true)) {
                    $checkAccess = true;
                }

                if($checkAccess == false) {
                    DB::rollback();
                    session()->flash('error', 'Anda tidak memiliki akses pada '.($exam->exam_group_id ? 'Tryout' : 'latihan soal').' ini. Silakan lakukan transaksi terlebih dahulu.');
                    return redirect()->back();
                }
            }
            
            $this->removeOldFiles(); 

            if(!empty($exam->exam_group_id)) {
                ExamGroupUser::updateOrCreate(
                    ['exam_group_id' =>  $exam->exam_group_id, 'user_id' => Auth::user()->id],
                    ['is_finished' => 0, 'grade' => 0, 'grade_calculate' => 0, 'description' => null]
                );
            }

            $grade = Grade::where('exam_id', $id)->where('user_id', auth()->user()->id);

            if($request->repeat == 1) {
                $grade->delete();
            }

            $totalSection = QuestionTitle::find($exam->question_title_id)->total_section;

            $grade = $grade->first();

            if(!$grade) {
                $answerInsert = [];
                $grade = new Grade();
                $grade->category_id = $exam->category_id;
                $grade->lesson_category_id = $exam->lesson_category_id;
                $grade->lesson_id = $exam->lesson_id;
                $grade->exam_id = $exam->id;
                $grade->exam_group_id = $exam->exam_group_id;
                $grade->user_id = auth()->user()->id;
                $grade->duration = $exam->duration;    
                $grade->total_tolerance = $exam->total_tolerance;
                $grade->grade_calculate = 0;
                $grade->total_section = $totalSection;
                $grade->start_time = Carbon::now();
                $grade->end_time = Carbon::now()->addMinutes($exam->duration)->addSeconds(6);   
                $grade->answers = [];
                $grade->save();
                $grade->refresh();
            }

            if($exam->random_question == 1 || $exam->random_answer == 1) {
                $path = 'json/user-id-'.Auth::user()->id.'-question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            } else {
                $path = 'json/question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            }
            
            if (!Storage::exists($path)) {

                if($exam->random_question == 1 || $exam->random_answer == 1) {
                    $files = glob(Storage::path('json/') . 'user-id-'.Auth::user()->id.'-question-id-'.$exam->questionTitle->id . '-*.json');
                } else {
                    $files = glob(Storage::path('json/') . 'question-id-'.$exam->questionTitle->id . '-*.json');
                }
            
                foreach($files as $file) {
                    File::delete($file);
                }
                
                $question_order = 0;
                $navigation_order = 0;

                $query = DB::table('questions')->where('question_title_id', $exam->question_title_id);
                $exam->random_question == 1 ? $query->inRandomOrder() : $query->orderBy('created_at', 'ASC');
                $exam->question_selection_mode == 2 ? $query->limit($exam->number_of_questions) : null;
                $questions = $query->get();
                
                foreach ($questions as $question) {
                    $navigation_order++;
                    $question_order++;

                    $options = [];
                    if($question->option_1 != null) $options[] = 1;
                    if($question->option_2 != null) $options[] = 2;
                    if($question->option_3 != null) $options[] = 3;
                    if($question->option_4 != null) $options[] = 4;
                    if($question->option_5 != null) $options[] = 5;
        
                    if($exam->random_answer == 1) {
                        shuffle($options);
                    }

                    $question->question_id = $question->id;
                    $question->navigation_order = $navigation_order;
                    $question->question_order = $question_order;
                    $question->question_answer = json_decode($question->answer, true);
                    $question->total_answer_limit = $question->total_answer_limit;
                    $question->answer_order = implode(",", $options);
                    $question->answer = [];
                    $question->is_correct = 'N';
                }

                Storage::put($path, json_encode($questions));
            }

            DB::commit();

            return redirect()->route('user.exams.exam-show-questions', [$grade->exam_id, $grade->id, $grade->section]);

        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            return response()->json(['error' => $e], 500);
        }
    }

    public function examShowQuestion($examId, $gradeId, $section, Request $request)
    {
        try {
            $exam = Exam::with(['lesson', 'examGroup'])->find($examId);

            if($exam->random_question == 1 || $exam->random_answer == 1) {
                $path = 'json/user-id-'.Auth::user()->id.'-question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            } else {
                $path = 'json/question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            }

            if (!Storage::exists($path)) {
                session()->flash('error', 'Ada Pembaruan Soal, Silakan Untuk Melakukan Refresh Halaman.');
                return redirect()->route('user.exams.exam-start', $exam->id);
            }
            
            $grade = Grade::find($gradeId);

            if ($grade->is_finished == 1) {
                return redirect()->route('user.categories.lesson-categories.lessons.exams.show', [
                    $exam->category_id, $exam->lesson_category_id, $exam->lesson_id, $exam->id
                ]);
            }

            if ($section != $grade->section && empty($request->nextsection)) {
                return redirect()->route('user.exams.exam-show-questions', [$grade->exam_id, $grade->id, $grade->section]);
            }

            if ($grade->section < $section) {
                $grade->update([
                    'section' => $grade->section + 1,
                    'end_time' => Carbon::now()->addMinutes($exam->duration)->addSeconds(6),
                ]);
                $grade->refresh();
            }

            $duration = ($grade->end_time > Carbon::now() || empty($grade->end_time)) ? $grade->end_time->diffInMilliseconds(Carbon::now()) : 0;

            $json = Storage::get($path);
            $gradeAnswers = json_decode($json, true);

            if(empty($gradeAnswers) || $gradeAnswers == null) {
                if ($grade) { $grade->delete();}

                session()->flash('error', 'Soal Dalam Latihan / Tryout Tidak Tersedia. Silakan Hubungi Admin.');

                if($exam->exam_group_id) {
                    return redirect()->route('user.exam_group.show', [
                        'id' => $exam->category_id, 
                        'lessonCategoryId' => $exam->lesson_category_id, 
                        'examGroupId' => $exam->exam_group_id, 
                        'exam' => $exam->id
                    ]);
                } else {
                    return redirect()->route('user.categories.lesson-categories.lessons.exams.show', [
                        'category' => $exam->category_id, 
                        'lesson_category' => $exam->lesson_category_id, 
                        'lesson' => $exam->lesson_id, 
                        'exam' => $exam->id
                    ]);
                }
            }

            $questionLists = array_values(array_filter($gradeAnswers, fn($var) => $var['section'] == $section));
            $totalQuestions = count($questionLists);

            return inertia('User/TryOut/Exam/Question', [
                'exam' => $exam,
                'setting' => Setting::first(),
                'grade' => $grade,
                'questionLists' => $questionLists,
                'duration' => $duration,
                'section' => (int)$grade->section,
                'indexPage' => 0,
                'lastSection' => (int)$grade->total_section,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e], 500);
        }
    }

    /**
     * decrementTolerance
     *
     * @param  Request $request
     * @return void
     */
    public function decrementTolerance(Request $request)
    {
        $grade = Grade::find($request->grade_id);

        if($request->warning == true) {
            $grade->update(['total_tolerance' => $grade->exam->total_tolerance, 'is_blocked' => 0]);
        } else {
            $total_tolerance = $grade->total_tolerance > 0 && $grade->total_tolerance != null ? $grade->total_tolerance - 1 : 0;
            $is_blocked = $total_tolerance <= 0 ? 1 : 0;
            $grade->update(['total_tolerance' => $total_tolerance, 'is_blocked' => $is_blocked]);
        }
    }

    /**
     * endExam
     *
     * @param  mixed $request
     * @return void
     */
    public function endExam($examId, Request $request)
    {
        DB::beginTransaction();

        try {
            $exam = Exam::findOrFail($examId);
            $grade = Grade::findOrFail($request->grade_id);
            $grade->update(['answers' => $request->myAnswers]);

            if($exam->random_question == 1 || $exam->random_answer == 1) {
                $path = 'json/user-id-'.Auth::user()->id.'-question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            } else {
                $path = 'json/question-id-' .$exam->questionTitle->id.'-question-updated-at-'.$exam->questionTitle->updated_at->format('ymdhis').'-exam-updated-at-'.$exam->updated_at->format('ymdhis').'.json';
            }
            
            if (!Storage::exists($path)) {
                session()->flash('error', 'Ada Pembaruan Soal, Silakan Untuk Melakukan Refresh Halaman.');
                return redirect()->route('user.exams.exam-start', $exam->id);
            }

            $json = Storage::get($path);
            $gradeAnswers = json_decode($json, true);
        
            $gradeAnswers = array_map(function ($gradeAnswer) use ($grade) {
                $filteredData = array_filter($grade->answers, function ($item) use ($gradeAnswer) {
                    return $item['question_id'] === $gradeAnswer['question_id'];
                });
            
                if (!empty($filteredData)) {
                    $firstMatch = reset($filteredData);
                    $gradeAnswer['answer'] = $firstMatch['answer'];
                    $gradeAnswer['is_correct'] = $this->arraysEqual($firstMatch['answer'], $gradeAnswer['question_answer'] ?? []) ? "Y" : "N";
                }
            
                return $gradeAnswer;
            }, $gradeAnswers);            

            $totalCorrectPerSection = array_reduce(range(1, $exam->questionTitle->total_section), function ($carry, $i) use ($gradeAnswers) {
                $totalCorrect = array_filter($gradeAnswers, function ($var) use ($i) {
                    return ($var['is_correct'] == "Y" && $var['section'] == $i);
                });
                $carry[] = count($totalCorrect);
                return $carry;
            }, []);

            $totalAnswerPerSection = array_reduce(range(1, $exam->questionTitle->total_section), function ($carry, $i) use ($gradeAnswers) {
                $totalAnswer = array_filter($gradeAnswers, function ($var) use ($i) {
                    return (!empty($var['answer']) && $var['section'] == $i);
                });
                $carry[] = count($totalAnswer);
                return $carry;
            }, []);

            $totalCorrect = array_filter($gradeAnswers, function ($var) {
                return ($var['is_correct'] === "Y" && !empty($var['answer']));
            });

            $totalAnswer = array_filter($gradeAnswers, function ($var) {
                return (!empty($var['answer']));
            });

            $totalWrong = array_filter($gradeAnswers, function ($var) {
                return ($var['is_correct'] === "N");
            });

            $totalQuestionGrade = count($gradeAnswers);
            $count_correct_answer = count($totalCorrect);
            $count_wrong_answer = count($totalWrong);

            $grade_exam = 0;

            if ($exam->questionTitle->assessment_type == 3) {
                $grade_exam = array_reduce($totalCorrect, function ($carry, $correct) { return $carry + $correct['correct_point'];}, 0);
            } elseif ($exam->questionTitle->assessment_type == 4) {
                $grade_exam = array_reduce($totalAnswer, function ($carry, $correct) { return $carry + (empty($correct['answer']) ? 0 : $correct['point_' . $correct['answer'][0]]);}, 0);
            } else {
                $grade_exam = $count_correct_answer == 0 ? 0 : round($count_correct_answer / $totalQuestionGrade * 100, 2);
            }

            $examGroup = ExamGroup::with([
                'grade' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
            ])
            ->withCount('exam')
            ->withCount('grade')
            ->find($exam->exam_group_id);

            $gradeAnswers = array_map(function ($item) {
                unset($item['id']);
                unset($item['audio']);
                unset($item['section']);
                unset($item['option_1']);
                unset($item['option_2']);
                unset($item['option_3']);
                unset($item['option_4']);
                unset($item['option_5']);
                unset($item['question']);
                unset($item['created_at']);
                unset($item['deleted_at']);
                unset($item['discussion']);
                unset($item['deleted_at']);
                unset($item['updated_at']);
                unset($item['audio_input']);
                unset($item['wrong_point']);
                unset($item['question_order']);
                unset($item['question_answer']);
                unset($item['audio_answer_time']);
                unset($item['question_title_id']);
                unset($item['audio_played_limit']);
                unset($item['total_answer_limit']);
                unset($item['discussion_video']);
                
                return $item;
            }, $gradeAnswers);

            $updates = [
                'end_time' => now(),
                'total_answer' => count($totalAnswer),
                'total_answer_per_section' => $totalAnswerPerSection,
                'total_correct' => $count_correct_answer,
                'total_wrong' => $count_wrong_answer,
                'total_correct_per_section' => $totalCorrectPerSection,
                'grade' => $grade_exam,
                'is_finished' => 1,
                'answers' => serialize($gradeAnswers),
            ];

            if ($examGroup && $examGroup->assessment_type == 2) {
                $updates['percentage_grade'] = $exam->percentage_grade ?? 0;
                $updates['final_grade'] = $grade_exam * ($updates['percentage_grade'] / 100);
            } else {
                $updates['final_grade'] = $grade_exam;
            }

            $grade->update($updates);

            DB::commit();

            if ($examGroup) {

                if($examGroup->exam_group_navigation_mode == 2) {
                    $examOrder = $examGroup->exam->pluck('id')->toArray();
                    $completedExams = Grade::where(['user_id' => Auth::user()->id, 'exam_group_id' => $examGroup->id, 'is_finished' => 1])->pluck('exam_id')->toArray();

                    foreach ($examOrder as $examId) {
                        if (!in_array($examId, $completedExams)) {
                            return redirect()->route('user.exams.exam-start', ['id' => $examId]);
                        }
                    }
                }

                if(Grade::where(['user_id' => Auth::user()->id, 'exam_group_id' => $examGroup->id])->count() == $examGroup->exam->count()) {
                    $examGroupUser = ExamGroupUser::where(['exam_group_id' => $examGroup->id, 'user_id' => Auth::user()->id])->first();

                    if($examGroupUser) {
                        return redirect()->route('user.exam-groups.histories.detail', $examGroupUser->id);
                    }
                }

                return redirect()->route('user.exam_group.show', [
                    $examGroup->category_id,
                    $examGroup->lesson_category_id,
                    $examGroup->id
                ]);
                
            } else {
                return redirect()->route('user.categories.lesson-categories.lessons.exams.show', [
                    $exam->category_id,
                    $exam->lesson_category_id,
                    $exam->lesson_id,
                    $exam->id
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function arraysEqual($arr1, $arr2) {
        sort($arr1);
        sort($arr2);
    
        return ($arr1 === $arr2) ? true : false;
    }

    public function removeOldFiles()
    {
        $directoryPath = storage_path('app/json');
        $currentDate = Carbon::now();
        $thresholdDate = $currentDate->subDays(7);

        $oldFiles = [];
        $allFiles = File::allFiles($directoryPath);

        foreach ($allFiles as $file) {
            $fileCreationTime = Carbon::createFromTimestamp($file->getCTime());
            if ($fileCreationTime->lessThan($thresholdDate)) {
                $oldFiles[] = $file->getPathname();
            }
        }

        foreach ($oldFiles as $file) {
            File::delete($file);
        }
    }

    public function unblocked(Request $request, $examId, $gradeId)
    {
        try {

            $exam = Exam::find($examId);

            if($request->token == $exam->unblock_token) {
                $grade = Grade::find($gradeId)->update(['is_blocked' => 0, "total_tolerance" => $exam->total_tolerance]);
                return response()->json([ "success" => true, "message" => "Membuka Blokir Berhasil"], 200);

            } else {
                return response()->json([ "success" => false, "message" => "Kode Tidak Cocok"], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function finished(Request $request, $examId, $gradeId)
    {
        try {
            $grade = Grade::find($gradeId);
            return response()->json([ "success" => true, "is_finished" => $grade->is_finished == 1 ? true : false], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
