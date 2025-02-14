<?php

namespace App\Repositories\Setting;

use App\Models\Setting\Banner;
use App\Repositories\Contracts\Setting\BannerInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;

class BannerRepository extends BaseRepository implements BannerInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/banners';

    public function __construct()
    {
        $this->model = new Banner();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $banners = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $banners->where('name', 'like', '%' . $params->search . '%');
        $banners = $banners->orderBy('order', 'ASC')->paginate($limit);

        $banners->appends([
            'search' => $params->search,
        ]);

        return $banners;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        if($attributes->hasFile('image')){
            $file = $attributes->file('image')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('image'), $this->image_path);
            $input['image'] = $filename;
        }

        return $this->model->create($input);
    }

    public function update($attributes, $id)
    {
        $banner = $this->model->find($id);
        $input = $attributes->all();

        if($attributes->hasFile('image')){
            $file = $attributes->file('image')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('image'), $this->image_path);
            (new UploadService())->deleteFile($banner->image, $this->image_path);
            $input['image'] = $filename;
        } else {
            $input['image'] = $banner->image;
        }

        return $banner->update($input);
    }

    public function delete($id)
    {
        $banner = $this->model->find($id);
        (new UploadService())->deleteFile($banner->image, $this->image_path);
        return $banner->delete();
    }
}
