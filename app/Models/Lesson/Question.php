<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Setting\Setting;
use App\Models\Lesson\QuestionTitle;
use App\Models\Lesson\ValueCategory;

class Question extends Model
{
    use HasFactory ,Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
    
    protected $casts = [
        'answer' => 'array',
    ];

    protected $fillable = [
        'id',
        'question_title_id',
        'value_category_id',
        'question',

        'audio_input',
        'audio',
        'audio_played_limit',
        'audio_answer_time',

        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'option_5',

        'point_1',
        'point_2',
        'point_3',
        'point_4',
        'point_5',

        'wrong_point',
        'correct_point',
        
        'answer',
        'total_answer_limit',
        'discussion_video',
        'discussion',
        'section',
        
        'created_at',
        'updated_at',
    ];

    public function questionTitle()
    {
        return $this->belongsTo(QuestionTitle::class);
    }

    public function valueCategory()
    {
        return $this->belongsTo(ValueCategory::class);
    }
}
