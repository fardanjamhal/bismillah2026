<?php

namespace App\Repositories\Affiliate;

use App\Models\Affiliate\UserBank;
use App\Repositories\Contracts\Affiliate\UserBankInterface;
use App\Repositories\BaseRepository;

class UserBankRepository extends BaseRepository implements UserBankInterface
{
    /**
     * @var
     */
    protected $model;
    
    public function __construct()
    {
        $this->model = new UserBank();
    }

    public function getAllByUserPaginatedWithParams($userId, $params, $limit = 10)
    {
        $userBank = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $userBank->where('bank_name', 'like', '%' . $params->search . '%');
        $userBank = $userBank->where('user_id', $userId)->orderBy('created_at', 'ASC')->paginate($limit);

        $userBank->appends([
            'search' => $params->search,
        ]);

        return $userBank;
    }

    public function getAllByUser($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'ASC')->get();
    }
}
