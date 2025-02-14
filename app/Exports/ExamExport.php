<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Exam\ExamGroup;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\GradeRepository;
use App\Models\Setting\Setting;

class ExamExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $rankingExams = (new GradeRepository())->getGradeByExam($this->id);
        $exam = (new ExamRepository())->find($this->id);

        $setting = Setting::first();
        $fields = collect($setting['authentication_field'])->where('is_active', '1')->keyBy('name');

        return view('report.excel.exam', [
            'exam' => $exam,
            'rankingExams' => $rankingExams,
            'fields' => $fields,
        ]);
    }
}
