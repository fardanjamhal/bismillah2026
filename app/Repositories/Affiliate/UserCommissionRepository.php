<?php

namespace App\Repositories\Affiliate;

use App\Models\Affiliate\UserCommission;
use App\Repositories\Contracts\Affiliate\UserCommissionInterface;
use App\Repositories\BaseRepository;

class UserCommissionRepository extends BaseRepository implements UserCommissionInterface
{
    /**
     * @var
     */
    protected $model;
    
    public function __construct()
    {
        $this->model = new UserCommission();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $userCommissions = $this->model->query();

        if (isset($params->search) && !empty($params->search)) {
            $userCommissions->whereHas('user', function ($query) use ($params) {
                $query->where(function ($subQuery) use ($params) {
                    $subQuery->where('name', 'like', '%' . $params->search . '%')
                             ->orWhere('email', 'like', '%' . $params->search . '%')
                             ->orWhere('username', 'like', '%' . $params->search . '%');
                });
            });
        }
    
        $userCommissions = $userCommissions->with(['user'])->orderBy('created_at', 'DESC')->paginate($limit);

        $userCommissions->appends([
            'search' => $params->search,
        ]);
    
        return $userCommissions;
    }

    public function summaryUserCommision()
    {
        return [
            'total_commission' => $this->model->sum('total_commission'),
            'total_withdrawals' => $this->model->sum('total_withdrawals'),
            'current_balance' => $this->model->sum('current_balance'),
        ];
    }
}
