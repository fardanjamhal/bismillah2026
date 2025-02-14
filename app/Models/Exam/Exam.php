<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\UserAccess;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\Lesson\LessonCategory;
use App\Models\Lesson\Lesson;
use App\Models\Lesson\QuestionTitle;
use App\Models\Exam\Grade;
use App\Models\MasterData\SubCategory;
use App\Models\Exam\ExamGroup;
use App\Models\MemberCategory;
use Auth;

class Exam extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'exam_group_id',
        'category_id',
        'lesson_category_id',
        'lesson_id',
        'question_title_id',
        'question_selection_mode',
        'number_of_questions',
        'title',
        'description',
        'duration',
        'random_question',
        'random_answer',
        'show_answer',
        'show_question_number_navigation',
        'show_question_number',
        'next_question_automatically',
        'show_prev_next_button',
        'type_option',
        'button_type_finish',
        'percentage_grade',
        'repeat_the_exam',
        'show_ranking_exam',
        'show_answer_discussion',
        'exam_start_time',
        'exam_end_time',
        'exam_status',
        'release_date',
        'total_tolerance',
        'unblock_token',
        'price_type',
        'price_before_discount',
        'price_after_discount',
        'member_categories',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function questionTitle()
    {
        return $this->belongsTo(QuestionTitle::class);
    }

    public function grade()
    {
        return $this->hasMany(Grade::class);
    }

    public function gradeFinished()
    {
        return $this->hasMany(Grade::class)->where('is_finished', 1);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class);
    }

    public function examGroup()
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class)->withTimestamps();
    }

    public function userAccess()
    {
        return $this->morphMany(UserAccess::class, 'access')->where('user_id', Auth::user()->id);
    }

    public function userAccessAll()
    {
        return $this->morphMany(UserAccess::class, 'access');
    }
}
