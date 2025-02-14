<?php

namespace App\Repositories\Setting;

use App\Models\Setting\InternetProtocolWhitelist;
use App\Repositories\Contracts\Setting\InternetProtocolWhitelistInterface;
use App\Repositories\BaseRepository;

class InternetProtocolWhitelistRepository extends BaseRepository implements InternetProtocolWhitelistInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new InternetProtocolWhitelist();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $ipWhitelists = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $ipWhitelists->where('ip', 'like', '%' . $params->search . '%');
        $ipWhitelists = $ipWhitelists->orderBy('created_at', 'ASC')->paginate($limit);

        $ipWhitelists->appends([
            'search' => $params->search,
        ]);
        return $ipWhitelists;
    }
}
