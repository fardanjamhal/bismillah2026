<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Exam\ExamGroup;
use App\Models\Exam\Grade;
use App\Models\Setting\Setting;

class ExamGroupExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $examGroup = ExamGroup::with([
            'lessonCategory',
            'category',
            'exam',
        ])->find($this->id);

        $setting = Setting::first();
        $fields = collect($setting['authentication_field'])->where('is_active', '1')->keyBy('name');

        $examGroupUsers = $examGroup->examGroupUser()
            // ->where('is_finished', 1)
            ->with(['user.student.province', 'user.student.city'])
            ->orderBy('grade', 'desc')
            ->cursor();

        $userIds = $examGroupUsers->pluck('user_id');
        $examIds = $examGroup->exam->pluck('id');

        $grades = Grade::whereIn('user_id', $userIds)
            ->whereIn('exam_id', $examIds)
            ->get()
            ->groupBy(function ($grade) {
                return $grade->user_id . '-' . $grade->exam_id;
            });

        return view('report.excel.exam_group', [
            'examGroup' => $examGroup,
            'examGroupUsers' => $examGroupUsers,
            'grades' => $grades,
            'fields' => $fields,
        ]);
    }
}