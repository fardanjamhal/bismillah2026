<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Lesson\Lesson;
use App\Models\Lesson\DetailValueCategory;
use App\Models\Lesson\ValueCategoryGroup;

class ValueCategory extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'value_category_group_id',
        'lesson_id',
        'name',
        'assessment_type',
        'add_relative_score',
        'relative_score_formulation',
        'add_weighted_score',
        'weighted_score_formulation',
        'multiplier',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function valueCategoryGroup()
    {
        return $this->belongsTo(ValueCategoryGroup::class);
    }

    public function detailValueCategory()
    {
        return $this->hasMany(DetailValueCategory::class);
    }
}
