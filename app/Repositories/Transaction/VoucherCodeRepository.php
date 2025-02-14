<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\VoucherCode;
use App\Repositories\Contracts\Transaction\VoucherCodeInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;

class VoucherCodeRepository extends BaseRepository implements VoucherCodeInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new VoucherCode();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $voucherCodes = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $voucherCodes->where('code', 'like', '%' . $params->search . '%');
        $voucherCodes = $voucherCodes->withCount('transaction')->orderBy('created_at', 'ASC')->paginate($limit);

        $voucherCodes->appends([
            'search' => $params->search,
        ]);

        return $voucherCodes;
    }
}
