<?php

namespace App\Http\Controllers\User\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\VoucherRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Models\Transaction\Transaction;
use Midtrans\Snap;
use Carbon\Carbon;
use DB;
use Auth;
use Str;

class VoucherController extends Controller
{
    protected $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Transaction/Voucher/Index', [
            'vouchers' => $this->voucherRepository->getAllActivated($request)
        ]);
    }
}
