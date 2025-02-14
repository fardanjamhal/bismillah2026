<?php

namespace App\Repositories\Material;

use App\Models\Material\Module;
use App\Repositories\Contracts\Material\ModuleInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;

class ModuleRepository extends BaseRepository implements ModuleInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/modules';

    public function __construct()
    {
        $this->model = new Module();
    }

    public function find($id)
    {
        return $this->model->with(['category', 'userAccess', 'memberCategories', 'detailModule' => function ($query) {
            $query->orderBy('order', 'ASC');
        }])->find($id);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $modules = $this->model->query();

        if(isset($params->search) && !empty($params->search)) {
            $modules->where('title', 'like', '%' . $params->search . '%');
        }

        $modules = $modules->with('category', 'memberCategories')
                        ->withCount('detailModule')
                        ->join('categories', 'modules.category_id', '=', 'categories.id')
                        ->orderBy('categories.order', 'ASC')
                        ->orderBy('modules.order', 'ASC')
                        ->paginate($limit);

        $modules->appends([
            'search' => $params->search,
        ]);

        return $modules;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        
        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            $input['thumbnail'] = $filename;
        }

        $module = $this->model->create($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $module->memberCategories()->sync($memberCategoryIds);
        }

        return $module;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $module = $this->find($id);

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            (new UploadService())->deleteFile($module->thumbnail, $this->image_path);
            $input['thumbnail'] = $filename;
        } else {
            $input['thumbnail'] = $module->thumbnail;
        }

        $memberCategories = [];

        $module->update($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $module->memberCategories()->sync($memberCategoryIds);
        }

        return $module;
    }

    public function delete($id)
    {
        $module = $this->model->find($id);
        (new UploadService())->deleteFile($module->thumbnail, $this->image_path);
        return $module->delete();
    }
}
