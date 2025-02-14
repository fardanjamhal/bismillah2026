<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\ValueCategoryGroup;
use App\Repositories\Contracts\Lesson\ValueCategoryGroupInterface;
use App\Repositories\BaseRepository;

class ValueCategoryGroupRepository extends BaseRepository implements ValueCategoryGroupInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ValueCategoryGroup();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $valueCategoryGroups = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $valueCategoryGroups->where('name', 'like', '%' . $params->search . '%');
        $valueCategoryGroups = $valueCategoryGroups->with(['category'])->orderBy('created_at', 'ASC')->paginate($limit);
       
        $valueCategoryGroups->appends([
            'search' => $params->search,
        ]);
        
        return $valueCategoryGroups;
    }

    public function getWithDetailValueCategory($id)
    {
        return $this->model->with(['valueCategory' => function($query) {
                $query->orderBy('created_at', 'ASC') // Order by di level valueCategory
                    ->with(['detailValueCategory' => function($detailQuery) {
                        $detailQuery->orderBy('created_at', 'ASC'); // Order by di level detailValueCategory
                    }]);
            }])
            ->whereHas('valueCategory.detailValueCategory')
            ->orderBy('created_at', 'ASC') // Order by di level tabel utama (model)
            ->find($id);
    }
}
