<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\User;
use App\Models\Exam\ExamGroup;

class ExamGroupUser extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'answers' => 'json',
        'end_time' => 'datetime:Y-m-d H:i:s',
        'start_time' => 'datetime:Y-m-d H:i:s',
    ];
    
    protected $fillable = [
        'id',
        'user_id',
        'exam_group_id',
        
        'duration',
        'section',
        'total_section',
        'start_time',
        'end_time',
        'total_correct',

        'answers',
        'total_tolerance',
        'is_blocked',
        'grade',
        'is_finished',
        'passed',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examGroup()
    {
        return $this->belongsTo(ExamGroup::class);
    }
}
