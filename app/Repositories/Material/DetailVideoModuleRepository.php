<?php

namespace App\Repositories\Material;

use App\Models\Material\DetailVideoModule;
use App\Repositories\Contracts\Material\DetailVideoModuleInterface;
use App\Repositories\BaseRepository;

class DetailVideoModuleRepository extends BaseRepository implements DetailVideoModuleInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new DetailVideoModule();
    }

    public function getAllBymodulePaginatedWithParams($videoModuleId, $params, $limit = 10)
    {
        $detailVideoModule = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $detailVideoModule->where('title', 'like', '%' . $params->search . '%');
        $detailVideoModule = $detailVideoModule->where('video_module_id', $videoModuleId)->orderBy('order', 'ASC')->paginate($limit);
       
        $detailVideoModule->appends([
            'search' => $params->search,
        ]);
        
        return $detailVideoModule;
    }
    
}
