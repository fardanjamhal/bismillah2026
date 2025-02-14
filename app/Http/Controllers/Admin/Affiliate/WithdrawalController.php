<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Affiliate\WithdrawalRepository;
use App\Models\Affiliate\UserCommission;
use App\Models\Affiliate\Withdrawal;
use App\Models\Setting\Setting;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    protected $setting, $withdrawalRepository;

    protected $image_path = 'upload_files/withdrawal_payment_confirmation';

    public function __construct(WithdrawalRepository $withdrawalRepository)
    {
        $this->setting = Setting::first();
        $this->withdrawalRepository = $withdrawalRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Affiliate/Withdrawal/Index', [
            'withdrawals' => $this->withdrawalRepository->getAllPaginatedWithParams($request),
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
        $withdrawal = $this->withdrawalRepository->find($id);

        if(!$withdrawal) {
            return abort('404');
        }

        return inertia('Admin/Affiliate/Withdrawal/Show')->with([
            'withdrawal' => $withdrawal,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
            'file' => 'required_if:status,approved',
        ]);

        $withdrawal = Withdrawal::find($id);

        if (!$withdrawal) {
            return redirect()->back()->with('error', 'Withdrawal not found.');
        }

        $input = $request->all();
        $input['date_approved'] = Carbon::now();

        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                $filename = (new UploadService())->uploadFile($request->file('file'), $this->image_path);
                (new UploadService())->deleteFile($withdrawal->file, $this->image_path);
                $input['file'] = $filename;
            } else {
                $input['file'] = $withdrawal->file;
            }

            if($request->status == 'approved') {
                $userCommission = UserCommission::where('user_id', $withdrawal->user_id)->first();

                if ($userCommission) {
                    $newTotalWithdrawals = $userCommission->total_withdrawals + $withdrawal->total_withdrawal;
                    $newCurrentBalance = $userCommission->current_balance - $withdrawal->total_withdrawal;

                    if ($newCurrentBalance < 0) {
                        return redirect()->back()->with('error', 'Saldo Tidak Cukup.');
                    }

                    $userCommission->update([
                        'total_withdrawals' => $newTotalWithdrawals,
                        'current_balance' => $newCurrentBalance,
                    ]);
                }
            }

            $withdrawal->update($input);

            DB::commit();

            return redirect()->route('admin.withdrawals.index')->with('success', 'Withdrawal updated successfully.');
        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
