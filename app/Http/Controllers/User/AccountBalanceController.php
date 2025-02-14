<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\BankRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\Transaction\TransactionRepository;
use Auth;
use App\Models\User;
use App\Models\Transaction\Transaction;
use App\Http\Requests\User\AccountBalanceRequest;
use App\Models\Transaction\VoucherCode;
use Carbon\Carbon;

class AccountBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/AccountBalance/Index', [
            'transactions' => (new TransactionRepository())->getTransactionAccountBalanceByUser(),
            'banks' => (new BankRepository())->all()
        ]);
    }

    public function store(AccountBalanceRequest $request)
    {
        return redirect()->route('user.transactions.buy', ['type' => 'topupBalance', 'id' => $request->top_up_balance]);
    }

    public function reedemVoucher(Request $request)
    {
        try {
            $voucherCode = VoucherCode::withCount('transaction')->where('code', $request->code)->first();

            if($voucherCode) {
                $voucherIsUsed = Transaction::where(['user_id' => Auth::user()->id, 'item_type' => 'App\Models\Transaction\VoucherCode', 'item_id' => $voucherCode->id])->first();

                if($voucherIsUsed) {
                    return response()->json(["success" => false, "message" => "Kode voucher sudah digunakan."], 200);
                }

                if($voucherCode->user_limit && $voucherCode->transaction_count >= $voucherCode->user_limit) {
                    return response()->json(["success" => false, "message" => "Limit kode voucher adalah ".$voucherCode->user_limit." orang dan sudah mencapai limit maksimal."], 200);
                }

                if($voucherCode->expired_date && $voucherCode->expired_date < Carbon::now()) {
                    return response()->json(["success" => false, "message" => "Kode voucher sudah kedaluarsa pada tanggal ". Carbon::parse($voucherCode->expired_date)->format('d M Y')], 200);
                }

                if($voucherCode->is_active == 0) {
                    return response()->json(["success" => false, "message" => "Kode Voucher Tidak Aktif."], 200);
                }

                $user = Auth::user();

                Transaction::create([
                    'user_id' => Auth::id(),
                    'item_type' => \App\Models\Transaction\VoucherCode::class,
                    'item_id' => $voucherCode->id,
                    'code' => Transaction::generateCode(),
                    'description' => 'Reedem Saldo Rp.'. number_format($voucherCode->nominal_voucher, 2, ",", "."),
                    'payment_method' => 'not_payment_method',
                    'total_payment' => $voucherCode->nominal_voucher,
                    'transaction_status' => 'done',
                ]);

                $user->update(['account_balance' => $user->account_balance + $voucherCode->nominal_voucher]);
                return response()->json(["success" => true, "message" => "Selamat saldo anda telah bertambah."], 200);

            } else {
                return response()->json(["success" => false, "message" => "Kode Voucher Tidak Ditemukan."], 200);
            }

        } catch (\Exception $e) {
            return $e;
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

}
