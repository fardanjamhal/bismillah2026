<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Lesson\LessonCategory;

class Lesson extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'lesson_category_id',
        'name',
        'short_name',
        'thumbnail',
        'order',
        'development_status'
    ];

    public function lessonCategory()
    {
        return $this->belongsTo(LessonCategory::class);
    }
}
