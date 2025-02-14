<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\News;
use App\Repositories\Contracts\MasterData\NewsInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;
class NewsRepository extends BaseRepository implements NewsInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/news';

    public function __construct()
    {
        $this->model = new News();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $news = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $news->where('title', 'like', '%' . $params->search . '%');
        $news = $news->with('user')->orderBy('created_at', 'ASC')->paginate($limit);

        $news->appends([
            'search' => $params->search,
        ]);
        return $news;
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['user_id'] = auth()->user()->id;
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
        $news = $this->model->find($id);
        (new UploadService())->deleteFile($news->thumbnail, $this->image_path);
        return $news->delete();
    }

    public function find($id)
    {
        return $this->model->with('user')->find($id);
    }
}
