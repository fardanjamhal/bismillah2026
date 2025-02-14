<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\Course;
use App\Models\Course\CourseSection;
use App\Traits\Uuid;

class CourseDetail extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'course_id',
        'course_section_id',
        'title',
        'link',
        'description',
        'order',
        'intro', // 0 locked , 1 opened;
        'is_active',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function children()
    {
        return $this->hasMany(CourseDetail::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CourseDetail::class, 'parent_id');
    }
}
