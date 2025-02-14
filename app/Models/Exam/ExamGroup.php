<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\UserAccess;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\Lesson\LessonCategory;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroupUser;
use App\Models\Exam\Grade;
use App\Models\MasterData\SubCategory;
use App\Models\Transaction\Transaction;
use App\Models\MemberCategory;
use Auth;

class ExamGroup extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'lesson_category_id',
        'title',
        'description',

        'exam_group_type',
        'exam_group_navigation_mode',
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
        'repeat_the_exam',
        'show_ranking_exam',
        'total_tolerance',
        'unblock_token',

        'assessment_type',
        'minimal_grade_type',
        'minimal_grade',
        'description_grade_less_than_minimal',
        'description_grade_more_than_minimal',
        'smallest_value_limit',
        'biggest_value_limit',
        'certificate_print_user',
        'exam_start_time',
        'exam_end_time',
        'exam_status',    
        'release_date',
        'price_type',
        'price_before_discount',
        'price_after_discount',
        'created_at',
        'updated_at',
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

    public function exam()
    {
        return $this->hasMany(Exam::class)->orderBy('created_at', 'ASC');
    }

    public function examGroupUser()
    {
        return $this->hasMany(ExamGroupUser::class);
    }

    public function grade()
    {
        return $this->hasMany(Grade::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class);
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
