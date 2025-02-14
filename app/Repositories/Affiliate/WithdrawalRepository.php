<?php

namespace App\Repositories\Affiliate;

use App\Models\Affiliate\Withdrawal;
use App\Repositories\Contracts\Affiliate\WithdrawalInterface;
use App\Repositories\BaseRepository;

class WithdrawalRepository extends BaseRepository implements WithdrawalInterface
{
    /**
     * @var
     */
    protected $model;
    
    public function __construct()
    {
        $this->model = new Withdrawal();
    }

    public function getByUser($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function find($id)
    {
        return $this->model->with(['user', 'userBank'])->find($id);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $withdrawals = $this->model->query();

        if (isset($params->search) && !empty($params->search)) {
            $withdrawals->whereHas('user', function ($query) use ($params) {
                $query->where(function ($subQuery) use ($params) {
                    $subQuery->where('name', 'like', '%' . $params->search . '%')
                             ->orWhere('email', 'like', '%' . $params->search . '%')
                             ->orWhere('username', 'like', '%' . $params->search . '%');
                });
            });
        }
    
        $withdrawals = $withdrawals->with(['user'])->orderBy('created_at', 'DESC')->paginate($limit);

        $withdrawals->appends([
            'search' => $params->search,
        ]);
    
        return $withdrawals;
    }    
}
