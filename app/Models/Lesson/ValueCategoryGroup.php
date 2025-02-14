<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use  App\Models\Lesson\ValueCategory;

class ValueCategoryGroup extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'show_calculator'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function valueCategory()
    {
        return $this->hasMany(ValueCategory::class);
    }  
}
