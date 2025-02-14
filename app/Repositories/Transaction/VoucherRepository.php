<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\Voucher;
use App\Repositories\Contracts\Transaction\VoucherInterface;
use App\Repositories\BaseRepository;

class VoucherRepository extends BaseRepository implements VoucherInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Voucher();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $vouchers = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $vouchers->where('name', 'like', '%' . $params->search . '%');
        $vouchers = $vouchers->with(['category', 'memberCategories'])->orderBy('created_at', 'ASC')->paginate($limit);
        
        $vouchers->appends([
            'search' => $params->search,
        ]);

        return $vouchers;
    }

    public function find($id)
    {
        return $this->model->with(['category', 'memberCategories'])->find($id);
    }

    public function getAllActivated($params)
    {
        return $this->model->with(['category', 'memberCategories'])->where('is_active', 1)->where('category_id', $params->category_id)->orderBy('created_at', 'ASC')->get();
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        $voucher = $this->model->create($input);

        $memberCategoryIds = [];

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }

        $voucher->memberCategories()->sync($memberCategoryIds);

        return $voucher;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $voucher = $this->model->find($id);

        $voucher->update($input);

        $memberCategoryIds = [];

        foreach ($attributes->member_category_ids as $memberCategory) {
            $memberCategoryIds[] = $memberCategory['id'];
        }

        $voucher->memberCategories()->sync($memberCategoryIds);

        return $voucher;
    }

    public function getAllByCategory($categoryId)
    {
        return $this->model->where(['category_id' => $categoryId, 'is_active' => 1])->orderBy('created_at', 'ASC')->get();
    }
}
