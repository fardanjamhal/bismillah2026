<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\Lesson\LessonCategory;
use App\Models\Lesson\ValueCategoryGroup;
use App\Models\Lesson\Lesson;
use App\Models\Lesson\Question;
use App\Models\Exam\Exam;

class QuestionTitle extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'lesson_category_id',
        'value_category_group_id',
        'lesson_id',
        'name',
        'total_choices',
        'total_section',
        'add_value_category',
        'assessment_type',
        'passing_grade',
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
    
    public function valueCategoryGroup()
    {
        return $this->belongsTo(ValueCategoryGroup::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function question()
    {
        return $this->hasMany(Question::class);
    }

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }
}
