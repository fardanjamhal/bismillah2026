<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Category;
use App\Repositories\Contracts\MasterData\CategoryInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;
use App\Models\Setting\Setting;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/categories';

    public function __construct()
    {
        $this->model = new Category();
        $this->setting = Setting::first();
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('order', 'ASC')->get($columns);
    }

    public function getCategoryWithValueCategoryGroup()
    {
        return $this->model->with(['valueCategoryGroup' => function($query) {
                $query->where('show_calculator', 1)->orderBy('created_at', 'DESC');
            }])
            ->whereHas('valueCategoryGroup')
            ->orderBy('order', 'ASC')
            ->get();
    }

    public function getAllProduction()
    {
        return $this->model->where('development_status', 'production')->orderBy('order', 'ASC')->get();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $categories = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $categories->where('name', 'like', '%' . $params->search . '%');
        $categories = $categories->orderBy('order', 'ASC')->paginate($limit);

        $categories->appends([
            'search' => $params->search,
        ]);

        return $categories;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            $input['thumbnail'] = $filename;
        }

        return $this->model->create($input);
    }

    public function update($attributes, $id)
    {
        $category = $this->model->find($id);
        $input = $attributes->all();

        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            (new UploadService())->deleteFile($category->thumbnail, $this->image_path);
            $input['thumbnail'] = $filename;
        } else {
            $input['thumbnail'] = $category->thumbnail;
        }

        return $category->update($input);
    }

    public function delete($id)
    {
        $category = $this->model->find($id);
        (new UploadService())->deleteFile($category->thumbnail, $this->image_path);
        return $category->delete();
    }

    public function getCategoryModules()
    {
        return $this->model->with(['module' => function ($query) {
            $query->whereIn('status', $this->setting->module_material_statuses)
                  ->orderBy('order', 'ASC')
                  ->with(['memberCategories', 'userAccess']);
        }])
        ->whereHas('module', function ($query) {
            $query->whereIn('status', $this->setting->module_material_statuses);
        })
        ->where('development_status', 'production')
        ->orderBy('order', 'ASC')
        ->get();
    }

    public function getCategoryVideoModules()
    {
        return $this->model->with(['videoModule' => function ($query) {
            $query->whereIn('status', $this->setting->video_module_statuses)
                  ->orderBy('order', 'ASC')
                  ->with(['memberCategories', 'userAccess']);
        }])
        ->whereHas('videoModule', function ($query) {
            $query->whereIn('status', $this->setting->video_module_statuses);
        })
        ->where('development_status', 'production')
        ->orderBy('order', 'ASC')
        ->get();
    }

    public function changeStatus($status, $id)
    {
        return $this->model->find($id)->update(['development_status' => $status]);
    }
}
