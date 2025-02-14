<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseDetail;
use App\Traits\Uuid;

class CourseSection extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'course_id',
        'title',
        'order',
    ];

    public function courseDetail()
    {
        return $this->hasMany(CourseDetail::class);
    }
}
