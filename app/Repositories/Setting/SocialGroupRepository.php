<?php

namespace App\Repositories\Setting;

use App\Models\Setting\SocialGroup;
use App\Repositories\Contracts\Setting\SocialGroupInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;

class SocialGroupRepository extends BaseRepository implements SocialGroupInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/social_groups';

    public function __construct()
    {
        $this->model = new SocialGroup();
    }

    public function getlAll()
    {
        $socialGroups = $this->model->query();
        $socialGroups = $socialGroups->orderBy('order', 'ASC')->get();
        return $socialGroups;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $socialGroups = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $socialGroups->where('name', 'like', '%' . $params->search . '%');
        $socialGroups = $socialGroups->orderBy('order', 'ASC')->paginate($limit);

        $socialGroups->appends([
            'search' => $params->search,
        ]);

        return $socialGroups;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        if($attributes->hasFile('logo')){
            $file = $attributes->file('logo')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('logo'), $this->image_path);
            $input['logo'] = $filename;
        }

        return $this->model->create($input);
    }

    public function update($attributes, $id)
    {
        $socialGroup = $this->model->find($id);
        $input = $attributes->all();

        if($attributes->hasFile('logo')){
            $file = $attributes->file('logo')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('logo'), $this->image_path);
            (new UploadService())->deleteFile($socialGroup->logo, $this->image_path);
            $input['logo'] = $filename;
        } else {
            $input['logo'] = $socialGroup->logo;
        }

        return $socialGroup->update($input);
    }

    public function delete($id)
    {
        $socialGroup = $this->model->find($id);
        (new UploadService())->deleteFile($socialGroup->logo, $this->image_path);
        return $socialGroup->delete();
    }
}
