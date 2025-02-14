<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\PaymentMethod;
use App\Repositories\Contracts\Transaction\PaymentMethodInterface;
use App\Repositories\BaseRepository;

class PaymentMethodRepository extends BaseRepository implements PaymentMethodInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new PaymentMethod();
    }

    public function getAllActivated()
    {
        return $this->model->select('code', 'name', 'is_active')->where('is_active', 1)->get();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $paymentMethods = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $paymentMethods->where('code', 'like', '%' . $params->search . '%');
        $paymentMethods = $paymentMethods->orderBy('created_at', 'ASC')->paginate($limit);

        $paymentMethods->appends([
            'search' => $params->search,
        ]);

        return $paymentMethods;
    }

    public function findByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function update($request, $code)
    {
        $paymentMethod = $this->model->where('code', $code)->first();
        if($paymentMethod) {
            $paymentMethod->update($request);
        }
    }
}
