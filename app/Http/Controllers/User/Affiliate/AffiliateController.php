<?php

namespace App\Http\Controllers\User\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use Illuminate\Support\Str;
use App\Repositories\User\UserRepository;
use App\Repositories\Affiliate\CommissionRepository;
use App\Repositories\Affiliate\UserBankRepository;
use App\Repositories\Affiliate\WithdrawalRepository;
use App\Models\Affiliate\ReferralLink;
use App\Models\Affiliate\Withdrawal;
use Illuminate\Support\Facades\DB;
use Auth;

class AffiliateController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->setting = Setting::first() ?? [];

        if($this->setting->enable_affiliate_feature == 0) {
            return abort('403');                
        }
    }

    public function index()
    {
        $userCommission = (new UserRepository())->getUserCommision(Auth::user()->id);
        $commissions = (new CommissionRepository())->getByUser(Auth::user()->id);

        return inertia('User/Affiliate/Affiliate/Balance')->with([
            'setting' => $this->setting,
            'userCommission' => $userCommission,
            'commissions' => $commissions
        ]);
    }

    public function referrals()
    {
        $userCommission = (new UserRepository())->getUserCommision(Auth::user()->id);
        $commissions = (new CommissionRepository())->getByUser(Auth::user()->id);
        $userReferrals = (new UserRepository())->referrals();
        
        return inertia('User/Affiliate/Affiliate/Referral')->with([
            'setting' => $this->setting,
            'userCommission' => $userCommission,
            'commissions' => $commissions,
            'userReferrals' => $userReferrals,
        ]);
    }

    public function termCondition()
    {
        return inertia('User/Affiliate/Affiliate/TermCondition');
    }

    public function link()
    {
        $user = auth()->user();

        $referralLink = $user->referralLink;

        if (!$referralLink) {
            $referralLink = $user->referralLink()->create([
                'referral_code' => strtoupper(Str::random(10))
            ]);
        }

        return inertia('User/Affiliate/Affiliate/Link')->with([
            'setting' => $this->setting,
            'referralLink' => $referralLink,

        ]);
    }

    public function editLink($id)
    {
        $referralLink = ReferralLink::findOrFail($id);

        return inertia('User/Affiliate/Affiliate/UpdateLink')->with([
            'referralLink' => $referralLink
        ]);
    }

    public function updateLink(Request $request, $id)
    {
        $validatedData = $request->validate([
            'referral_code' => [
                'required',
                'max:20',
                'regex:/^\S*$/', // Tidak boleh ada spasi
            ],
        ], [
            'referral_code.required' => 'Kode referral wajib diisi.',
            'referral_code.max' => 'Kode referral tidak boleh lebih dari 20 karakter.',
            'referral_code.regex' => 'Format isian referral code tidak boleh ada spasi.', // Pesan kustom untuk regex
        ]);

        $referralLink = ReferralLink::findOrFail($id);
        $referralLink->update($validatedData);

        return redirect()->route('user.affiliates.links')->with('success', 'Link referral berhasil diperbarui.');
    }

    public function withdrawal()
    {
        $withdrawals = (new WithdrawalRepository())->getByUser(Auth::user()->id);
        
        return inertia('User/Affiliate/Affiliate/Withdrawal')->with([
            'withdrawals' => $withdrawals
        ]);
    }

    public function createWithdrawal()
    {
        $pendingWithdrawal = Withdrawal::whereIn('status', ['pending'])->where('user_id', Auth::user()->id)->first();

        if($pendingWithdrawal) {
            return redirect()->back()->with('error', 'Anda memiliki pengajuan yang saat ini berstatus pending. Harap menunggu hingga pengajuan tersebut disetujui');
        }

        $userCommission = (new UserRepository())->getUserCommision(Auth::user()->id);
        $userBanks = (new UserBankRepository())->getAllByUser(Auth::user()->id);

        return inertia('User/Affiliate/Affiliate/CreateWithdrawal')->with([
            'userCommission' => $userCommission,
            'userBanks' => $userBanks,
        ]);
    }

    public function storeWithdrawal(Request $request)
    {
        try {
            $userCommission = (new UserRepository())->getUserCommision(Auth::user()->id);
            $maxValue = $userCommission->userCommission->current_balance ?? 0;

            $this->validate($request, [
                'user_bank_id' => 'required',
                'total_withdrawal' => 'required|numeric|min:'.$this->setting->minimum_withdrawal.'|max:'.($userCommission && $userCommission->userCommission ? $userCommission->userCommission->current_balance : 0),
            ], [
                'total_withdrawal.max' => 'Isian total penarikan seharusnya tidak lebih dari Rp. '. number_format($maxValue, 0, ',', '.'),
                'total_withdrawal.min' => 'Isian total penarikan seharusnya tidak kurang dari Rp. ' . number_format($this->setting->minimum_withdrawal, 0, ',', '.'),
                'total_withdrawal.required' => 'Isian total penarikan wajib diisi.',
                'total_withdrawal.numeric' => 'Isian total penarikan harus berupa angka.',
                'user_bank_id.required' => 'Bidang isian bank wajib diisi.',
            ]);
            
            $pendingWithdrawal = Withdrawal::whereIn('status', ['pending'])->where('user_id', Auth::user()->id)->first();

            if($pendingWithdrawal) {
                return redirect()->back()->withInput()->with('error', 'Anda memiliki pengajuan yang saat ini berstatus pending. Harap menunggu hingga pengajuan tersebut disetujui.');
            }

            DB::connection()->beginTransaction();

            Withdrawal::create([
                'user_id' => Auth::user()->id,
                'user_bank_id' => $request->user_bank_id,
                'total_withdrawal' => $request->total_withdrawal,
                'admin_fee' => $this->setting->admin_fee,
                'total_paid' => $request->total_withdrawal - $this->setting->admin_fee,
                'status' => 'pending'
            ]);

            DB::connection()->commit();
            return redirect()->route('user.affiliates.withdrawals')->with('success', 'Penarikan komisi berhasil diajukan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::connection()->rollBack();
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::connection()->rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan penarikan. Silakan coba lagi nanti.'. $e);
        }
    }

    public function showWithdrawal($id)
    {
        $withdrawal = (new WithdrawalRepository())->find($id);

        if(!$withdrawal) {
            return abort('404');
        }

        return inertia('User/Affiliate/Affiliate/DetailWithdrawal')->with([
            'withdrawal' => $withdrawal,
        ]);
    }

}
