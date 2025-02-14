<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use App\Models\Lesson\LessonCategory;
use App\Models\Lesson\Lesson;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Models\User;

class Grade extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'answers' => 'json',
        'total_answer_per_section' => 'array',
        'total_correct_per_section' => 'array',
        'grade_details' => 'json',
        'end_time' => 'datetime:Y-m-d H:i:s',
        'start_time' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'id',
        'category_id',
        'lesson_category_id',
        'lesson_id',
        'exam_id',
        'exam_group_id',
        'user_id',
        'duration',
        'section',
        'total_section',
        'start_time',
        'end_time',
        'work_duration',
        'total_answer',
        'total_correct',
        'total_wrong',
        'total_answer_per_section',
        'total_correct_per_section',
        'percentage_grade',
        'grade',
        'final_score',
        'final_grade',
        'answers',
        'grade_details',
        'is_finished',
        'grade_calculate',
        'total_tolerance',
        'is_blocked'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lessonCategory()
    {
        return $this->belongsTo(LessonCategory::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function examGroup()
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
