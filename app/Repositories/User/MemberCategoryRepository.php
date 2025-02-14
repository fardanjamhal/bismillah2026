<?php

namespace App\Repositories\User;

use App\Models\MemberCategory;
use App\Repositories\Contracts\User\MemberCategoryInterface;
use App\Repositories\BaseRepository;
use Auth;

class MemberCategoryRepository extends BaseRepository implements MemberCategoryInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new MemberCategory();
    }

    public function getAllActivated()
    {
        return $this->model->where('status', 1)->orderBy('created_at', 'ASC')->get();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if(isset($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->orderBy('created_at', 'ASC')->paginate($limit);

        $users->appends([
            'search' => $params->search,
        ]);

        return $users;
    }
}
