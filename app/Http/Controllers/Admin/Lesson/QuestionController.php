<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\QuestionRepository;
use App\Repositories\Lesson\QuestionTitleRepository;
use App\Repositories\Lesson\ValueCategoryRepository;
use App\Repositories\Lesson\QuestionTemplateRepository;
use App\Http\Requests\Lesson\QuestionRequest;
use Ramsey\Uuid\Uuid as Generator;
use App\Models\Lesson\Question;
use App\Models\Lesson\QuestionTitle;
use App\Models\Exam\ExamGroup;
use Carbon\Carbon;
use Excel;
use App\Imports\QuestionImport;
use App\Exports\QuestionExport;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Imports\GenerateQuestionImport;
use Session;

class QuestionController extends Controller
{
    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($questionTitleId, Request $request)
    {
        if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

        Session::put('queryStringQuestions', $request->getQueryString());

        return inertia('Admin/Lesson/Question/Index', [
            'questions' => $this->questionRepository->getByQuestionTitlePaginatedWithParams($request, $questionTitleId),
            'questionTitle' => (new QuestionTitleRepository())->find($questionTitleId)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($questionTitleId)
    {
        if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

        $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);
        
        $valueCategories = (new ValueCategoryRepository())->findWhere(['lesson_id' => $questionTitle->lesson_id, 'value_category_group_id' => $questionTitle->value_category_group_id])->get();

        return inertia('Admin/Lesson/Question/Create', [
            'questionTitle' => $questionTitle,
            'valueCategories' => $valueCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($questionTitleId, QuestionRequest $request)
    {
        try {
            if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

            #store
            $this->questionRepository->create($request);

            #Bump....
            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

        } catch(\Exception $e) {
            #get message
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($questionTitleId, $id)
    {
        if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

        if(!$this->questionRepository->find($id)) return abort('404', 'uppss....');

        $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);

        $valueCategories = (new ValueCategoryRepository())->findWhere(['lesson_id' => $questionTitle->lesson_id, 'value_category_group_id' => $questionTitle->value_category_group_id])->get();

        $question =  $this->questionRepository->find($id);
        return inertia('Admin/Lesson/Question/Edit', [
            'questionTitle' => $questionTitle,
            'question' => $this->questionRepository->find($id),
            'valueCategories' => $valueCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($questionTitleId, QuestionRequest $request, $id)
    {
        try {
            if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

            if(!$this->questionRepository->find($id)) return abort('404', 'uppss....');

            $this->questionRepository->update($request, $id);

            $queryString = Session::get('queryStringQuestions');
            return redirect(route('admin.question-titles.questions.index', $questionTitleId) . (!empty($queryString) ? '?'.$queryString : ''));

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
    public function destroy($questionTitleId, $id)
    {
        try {
            if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');

            if(!$this->questionRepository->find($id)) return abort('404', 'uppss....');

            $this->questionRepository->delete($id);

            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back();
        }
    }

    public function importExcel(Request $request, $questionTitleId)
    {
        try {

            $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);

            $collection = Excel::toCollection(null, $request->file);
            $headingRow = $collection->first()->first();
            $headingArray = $headingRow->toArray();

            $headerFile = array_filter($headingArray, function ($value) {
                return $value !== null;
            });
            $headerFile = array_values($headerFile);

            $headerImport = [
                'NO',
            ];

            if ($questionTitle->assessment_type == 1) {
                $headerImport[] = 'KATEGORI_PENILAIAN';
            }

            if ($questionTitle->total_section >  1) {
                $headerImport[] = 'KOLOM_KE';
            }

            $headerImport[] = 'SOAL';

            for ($i = 0; $i < $questionTitle->total_choices; $i++) {
                $headerImport[] = 'PILIHAN_' . chr(65 + $i);
            }

            if ($questionTitle->assessment_type != 4) {
                $headerImport[] = 'JAWABAN';
            }

            if ($questionTitle->assessment_type == 4) {
                for ($i = 0; $i < $questionTitle->total_choices; $i++) {
                    $headerImport[] = 'BOBOT_JAWABAN_' . chr(65 + $i);
                }
            }

            if ($questionTitle->assessment_type == 3) {
                $headerImport[] = 'POIN_JAWABAN_BENAR';
            }

            $headerImport[] = 'PEMBAHASAN';
            
            if ($headerFile === $headerImport) {

                try {              
                    Excel::import(new QuestionImport($questionTitle), $request->file);

                    $questionTitle->update(['updated_at' => Carbon::now()]);

                    session()->flash('success', 'Proses Import Berhasil Dijalankan.');
                    return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
            
                    // Map terjemahan
                    $translationMap = [];
            
                    $errorMessages = [];
                    $rowErrors = [];
                    foreach ($failures as $failure) {
                        // Terjemahkan pesan kesalahan
                        $translatedErrors = array_map(function($error) use ($translationMap) {
                            return $translationMap[$error] ?? $error;
                        }, $failure->errors());
            
                        // Mendapatkan nilai dari kolom yang menyebabkan kesalahan
                        $values = $failure->values();
            
                        // Pastikan array values memiliki indeks yang sesuai
                        $value1 = isset($values[0]) ? $values[0] : '';
                        $value2 = isset($values[1]) ? $values[1] : '';
            
                        // Tambahkan pesan kesalahan baris ke dalam array
                        $rowErrors[$failure->row()][$value1][] = $translatedErrors[0] ?? '';
                        $rowErrors[$failure->row()][$value2][] = $translatedErrors[1] ?? '';
                    }
            
                    foreach ($rowErrors as $row => $errors) {
                        foreach ($errors as $value => $errorArray) {
                            // Gabungkan pesan kesalahan untuk email dan username
                            $errorMessage = 'Ada kesalahan pada baris ke ' . $row . '. ';
                            if (count(array_filter($errorArray)) == 2) {
                                $errorList = array_filter($errorArray);
                                $errorMessage .= implode(' dan ', array_unique($errorList));
                            } else {
                                // Jika hanya satu jenis kesalahan yang terjadi
                                $errorMessage .= implode(', ', array_unique(array_filter($errorArray)));
                            }
                            $errorMessages[] = $errorMessage;
                        }
                    }
            
                    // Menghapus pesan duplikat
                    $errorMessages = array_unique($errorMessages);
            
                    // Menggabungkan pesan kesalahan menjadi satu string
                    $errorMessage = implode(' ', $errorMessages);
            
                    // Redirect dengan pesan kesalahan
                    return redirect()->route('admin.question-titles.questions.index', $questionTitleId)->withErrors($errorMessage)->withInput();
                }

            } else {
                session()->flash('error', 'Format Import Tidak Sesuai. ');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file: ' . $e->getFile() . ' line: ' . $e->getLine());
            return redirect()->back();
        }
    }

    public function exportExcel($questionTitleId)
    {
        try {
            $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);
            return Excel::download(new QuestionExport($questionTitle), str_replace(' ', '_', $questionTitle->name).'.xlsx');

        } catch(\Exception $e) {
            #get message
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }

    public function exportPdf($questionTitleId)
    {
        try {
            $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);

            return view('report.pdf.question')->with([
                'questionTitle' => $questionTitle,
                'questions' => Question::where('question_title_id', $questionTitleId)->orderBy('created_at', 'ASC')->get(),
            ]);

        } catch(\Exception $e) {
            #get message
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }

    public function deleteQuestion($questionTitleId)
    {
        try {
            if(!(new QuestionTitleRepository())->find($questionTitleId)) return abort('404', 'uppss....');
            
            $questionTitle = QuestionTitle::find($questionTitleId);
            foreach($questionTitle->exam as $exam) {
                if(!empty($exam->exam_group_id)) {
                    ExamGroup::find($exam->exam_group_id)->update(['updated_at' => Carbon::now()]);
                }
            }

            $questionTitle->update(['updated_at' => Carbon::now()]);

            Question::where('question_title_id', $questionTitleId)->delete();

            #Bump....
            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

        } catch(\Exception $e) {
            #get message
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }

    public function generateQuestionCreate($questionTitleId)
    {
        $questionTemplates = (new QuestionTemplateRepository())->all();

        return inertia('Admin/Lesson/Question/GenerateQuestion', [
            'questionTemplates' => $questionTemplates,
            'questionTitle' =>  QuestionTitle::find($questionTitleId)
        ]);
    }

    public function generateQuestionStore(Request $request, $questionTitleId)
    {
        $questionTitle = (new QuestionTitleRepository())->find($questionTitleId);

        $request->validate([
            'template' => [
                'required_if:source_data,2',
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('source_data') == 2 && $value && !in_array($value->getClientOriginalExtension(), ['xlsx'])) {
                        $fail( $attribute . ' harus dokumen berjenis : xlsx.');
                    }
                }
            ],
        ]);

        $questionTemplate = (new QuestionTemplateRepository())->find($request->question_template_id);
        $template = $questionTemplate->template;
        
        if($request->source_data == 1) {
            $data = $request->all();
            $columns = [];
            $shortColumns = [];
    
            for ($i = 1; $i <= 10; $i++) {
                $columnData = mb_str_split($data["column_$i"]);
                if (count($columnData) < 5) {
                    $shortColumns[] = "column_$i";
                }
                $columns["column_$i"] = $columnData;
            }
    
            if (!empty($shortColumns)) {
                return response()->json([
                    'message' => 'Some columns have less than 5 elements.',
                    'columns' => $shortColumns
                ], 400);
            }
    
            $columnKeys = array_keys($columns);
    
            foreach ($columns as $index => $columnData) {
                $section = array_search($index, $columnKeys) + 1;
                $columnData = array_slice($columnData, 0, 5);
                while (count($columnData) < 5) {
                    $columnData[] = '';
                }
    
                for ($i = 0; $i < $request->total_question_per_section; $i++) {
                    $question = $this->generateQuestion($columnData, $section, $template);
                    
                    Question::insert([
                        'id' => Generator::uuid4()->toString(),   
                        'question_title_id' => $questionTitleId,    
                        'value_category_id' => null,
                        'question' => $question['question'],
                        'option_1' => $columnData[0],
                        'option_2' => $columnData[1],
                        'option_3' => $columnData[2],
                        'option_4' => $columnData[3],
                        'option_5' => $columnData[4],
                        'answer' => $question['correct_answer'],
                        'section' => $section,
                        'created_at' => Carbon::now()->addMinutes($section * 10),
                        'updated_at' => Carbon::now()->addMinutes($section * 10),
                    ]);
                }
            }
        } else {
            $collection = Excel::toCollection(null, $request->template);
            $headingRow = $collection->first()->first();
            $headingArray = $headingRow->toArray();

            $headerFile = array_filter($headingArray, function ($value) {
                return $value !== null;
            });

            $headerFile = array_values($headerFile);

            $headerImport = [
                'NO',	
                'COLUMN',	
                'QUESTION',	
                'ANSWER_TEXT_A',	
                'ANSWER_TEXT_B',	
                'ANSWER_TEXT_C',	
                'ANSWER_TEXT_D',	
                'ANSWER_TEXT_E',	
                'ANSWER',
            ];

            if ($headerFile === $headerImport) {
             

                try {
                    Excel::import(new GenerateQuestionImport($questionTitle, $template), $request->template);
                    return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
            
                    // Map terjemahan
                    $translationMap = [
                        'The no field is required' => 'No wajib diisi',
                        'The column field is required' => 'Column wajib diisi',
                        'The question field is required' => 'Question wajib diisi',
                        'The question must be exactly 4 characters' => 'Question harus tepat 4 karakter',
                        'The answer_text_a field is required' => 'Jawaban A wajib diisi',
                        'The answer_text_b field is required' => 'Jawaban B wajib diisi',
                        'The answer_text_c field is required' => 'Jawaban C wajib diisi',
                        'The answer_text_d field is required' => 'Jawaban D wajib diisi',
                        'The answer_text_e field is required' => 'Jawaban E wajib diisi',
                        'The answer field is required' => 'Jawaban wajib diisi',
                        'The answer must be one of A, B, C, D, E' => 'Jawaban harus salah satu dari: A, B, C, D, E',
                    ];
            
                    $errorMessages = [];
                    $rowErrors = [];
                    foreach ($failures as $failure) {
                        // Terjemahkan pesan kesalahan
                        $translatedErrors = array_map(function($error) use ($translationMap) {
                            return $translationMap[$error] ?? $error;
                        }, $failure->errors());
            
                        // Mendapatkan nilai dari kolom yang menyebabkan kesalahan
                        $values = $failure->values();
            
                        // Pastikan array values memiliki indeks yang sesuai
                        $value1 = isset($values[0]) ? $values[0] : '';
                        $value2 = isset($values[1]) ? $values[1] : '';
            
                        // Tambahkan pesan kesalahan baris ke dalam array
                        $rowErrors[$failure->row()][$value1][] = $translatedErrors[0] ?? '';
                        $rowErrors[$failure->row()][$value2][] = $translatedErrors[1] ?? '';
                    }
            
                    foreach ($rowErrors as $row => $errors) {
                        foreach ($errors as $value => $errorArray) {
                            // Gabungkan pesan kesalahan untuk email dan username
                            $errorMessage = 'Ada kesalahan pada baris ke ' . $row . '. ';
                            if (count(array_filter($errorArray)) == 2) {
                                $errorList = array_filter($errorArray);
                                $errorMessage .= implode(' dan ', array_unique($errorList));
                            } else {
                                // Jika hanya satu jenis kesalahan yang terjadi
                                $errorMessage .= implode(' ,', array_unique(array_filter($errorArray)));
                            }
                            $errorMessages[] = $errorMessage;
                        }
                    }
            
                    // Menghapus pesan duplikat
                    $errorMessages = array_unique($errorMessages);
            
                    // Menggabungkan pesan kesalahan menjadi satu string
                    $errorMessage = implode(' ', $errorMessages);
            
                    // Redirect dengan pesan kesalahan
                    return redirect()->route('admin.question-titles.questions.generate_question_templates', $questionTitleId)->withErrors($errorMessage)->withInput();
                }

            } else {
                session()->flash('error', 'Format Import Tidak Sesuai. ');
                return redirect()->back();
            }
        }

        $questionTitle->update(['updated_at' => Carbon::now()]);

        return redirect()->route('admin.question-titles.questions.index', $questionTitleId);
    }

    private function generateQuestion($columnData, $section, $template)
    {
        $letters = range('A', 'E');

        // Ensure $columnData has exactly 5 elements
        $columnData = array_slice($columnData, 0, 5);
        while (count($columnData) < 5) {
            $columnData[] = '';
        }

        $mapping = array_combine($columnData, $letters);

        // Shuffle the data
        $shuffledData = $columnData;
        shuffle($shuffledData);

        // Remove one element randomly
        $missingIndex = array_rand($shuffledData);
        $missingLetter = $shuffledData[$missingIndex];
        unset($shuffledData[$missingIndex]);

        // Reindex array
        $shuffledData = array_values($shuffledData);

        // Determine the correct answer
        $correctAnswer = array_search($missingLetter, $columnData) + 1; // Since A=1, B=2, etc.

        // Replace placeholders in template
        $replacements = [
            '{column}' => $section,
            '{oa}' => $columnData[0],
            '{ob}' => $columnData[1],
            '{oc}' => $columnData[2],
            '{od}' => $columnData[3],
            '{oe}' => $columnData[4],
            '{q1}' => $shuffledData[0] ?? '',
            '{q2}' => $shuffledData[1] ?? '',
            '{q3}' => $shuffledData[2] ?? '',
            '{q4}' => $shuffledData[3] ?? '',
        ];

        $questionHtml = str_replace(array_keys($replacements), array_values($replacements), $template);

        // Return the question and the correct answer
        return [
            'question' => $questionHtml,
            'correct_answer' => "[$correctAnswer]"
        ];
    }
}
