<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\SubCategory;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Models\Material\Module;
use App\Models\Material\VideoModule;
use App\Models\Course\Course;
use App\Models\Lesson\ValueCategoryGroup;

class Category extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'thumbnail',
        'order',
        'development_status',
    ];
    
    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }

    public function examGroup()
    {
        return $this->hasMany(ExamGroup::class);
    }

    public function module()
    {
        return $this->hasMany(Module::class);
    }

    public function videoModule()
    {
        return $this->hasMany(VideoModule::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function valueCategoryGroup()
    {
        return $this->hasMany(ValueCategoryGroup::class);
    }
}
