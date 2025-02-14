<?php

namespace App\Repositories\Material;

use App\Models\Material\DetailModule;
use App\Repositories\Contracts\Material\DetailModuleInterface;
use App\Repositories\BaseRepository;

class DetailModuleRepository extends BaseRepository implements DetailModuleInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new DetailModule();
    }

    public function getAllBymodulePaginatedWithParams($moduleId, $params, $limit = 10)
    {
        $detailModule = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $detailModule->where('title', 'like', '%' . $params->search . '%');
        $detailModule = $detailModule->where('module_id', $moduleId)->orderBy('order', 'ASC')->paginate($limit);
       
        $detailModule->appends([
            'search' => $params->search,
        ]);
        
        return $detailModule;
    }
    
}
