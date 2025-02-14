<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\SubCategory;
use App\Repositories\Contracts\MasterData\SubCategoryInterface;
use App\Repositories\BaseRepository;

class SubCategoryRepository extends BaseRepository implements SubCategoryInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new SubCategory();
    }

    public function getByCategoryId($categoryId)
    {
        return $this->model->where('category_id', $categoryId)->orderBy('order', 'ASC')->get();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $subCategories = $this->model->query();

        if (isset($params->search) && !empty($params->search)) {
            $subCategories->where('sub_categories.name', 'like', '%' . $params->search . '%');
        }

        $subCategories = $subCategories
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('sub_categories.*', 'categories.order as category_order')
            ->with('category')
            ->orderBy('categories.order', 'ASC') 
            ->orderBy('sub_categories.order', 'ASC')
            ->paginate($limit);

        $subCategories->appends([
            'search' => $params->search,
        ]);

        return $subCategories;
    }

}
