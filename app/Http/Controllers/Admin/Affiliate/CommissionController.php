<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Affiliate\UserCommissionRepository;
use App\Repositories\Affiliate\CommissionRepository;
use App\Repositories\Affiliate\WithdrawalRepository;
use App\Repositories\User\UserRepository;
use Auth;

class CommissionController extends Controller
{
    protected $userCommissionRepository;

    public function __construct(UserCommissionRepository $userCommissionRepository)
    {
        $this->userCommissionRepository = $userCommissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Affiliate/Commission/Index', [
            'userCommissions' => $this->userCommissionRepository->getAllPaginatedWithParams($request),
            'summaryUserCommission' => $this->userCommissionRepository->summaryUserCommision(),
            'commissionCount' => (new CommissionRepository())->commissionCount(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commision = $this->userCommissionRepository->find($id);
        
        if(!$commision) {
            return abort('404');
        }

        $userCommission = (new UserRepository())->getUserCommision($commision->user_id);
        $commissions = (new CommissionRepository())->getByUser($commision->user_id);
        $withdrawals = (new WithdrawalRepository())->getByUser($commision->user_id);

        return inertia('Admin/Affiliate/Commission/Show')->with([
            'userCommission' => $userCommission,
            'commissions' => $commissions,
            'withdrawals' => $withdrawals
        ]);
    }
}
