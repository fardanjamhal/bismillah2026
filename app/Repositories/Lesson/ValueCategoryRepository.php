<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\ValueCategory;
use App\Repositories\Contracts\Lesson\ValueCategoryInterface;
use App\Repositories\BaseRepository;

class ValueCategoryRepository extends BaseRepository implements ValueCategoryInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ValueCategory();
    }

    public function getAllByValueCategoryGroupPaginatedWithParams($valueCategoryGroupId, $params, $limit = 10)
    {
        $valueCategories = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $valueCategories->where('name', 'like', '%' . $params->search . '%');
        $valueCategories = $valueCategories->where('value_category_group_id', $valueCategoryGroupId)->with(['lesson','lesson.lessonCategory', 'lesson.lessonCategory.category'])->orderBy('created_at', 'ASC')->paginate($limit);
       
        $valueCategories->appends([
            'search' => $params->search,
        ]);
        
        return $valueCategories;
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['relative_score_formulation'] = $attributes->add_relative_score == 0 ? null : $attributes->relative_score_formulation;
        $input['weighted_score_formulation'] = $attributes->add_weighted_score == 0 ? null : $attributes->weighted_score_formulation;

        return $this->model->create($input);
    }

    public function update($attributes, $id)
    {
        $valueCategory = $this->model->find($id);

        $input = $attributes->all();
        $input['relative_score_formulation'] = $attributes->add_relative_score == 0 ? null : $attributes->relative_score_formulation;
        $input['weighted_score_formulation'] = $attributes->add_weighted_score == 0 ? null : $attributes->weighted_score_formulation;

        return $valueCategory->update($input);
    }
}
