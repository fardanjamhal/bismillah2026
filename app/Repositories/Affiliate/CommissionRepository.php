<?php

namespace App\Repositories\Affiliate;

use App\Models\Affiliate\Commission;
use App\Repositories\Contracts\Affiliate\CommissionInterface;
use App\Repositories\BaseRepository;

class CommissionRepository extends BaseRepository implements CommissionInterface
{
    /**
     * @var
     */
    protected $model;
    
    public function __construct()
    {
        $this->model = new Commission();
    }

    public function getByUser($userId)
    {
        return $this->model->where('user_id', $userId)->with(['transaction.user', 'user'])->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function commissionCount($conditions = [])
    {
        $query = $this->model->query();

        if (!empty($conditions)) {
            $query->where($conditions);
        }

        return $query->count();
    }
}
