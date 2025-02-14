<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\Transaction;
use App\Repositories\Contracts\Transaction\TransactionInterface;
use App\Repositories\BaseRepository;
use Auth;
use Carbon\Carbon;
use App\Models\Setting\Setting;
use App\Models\UserMemberCategory;
use App\Models\Affiliate\Commission;
use App\Models\Affiliate\UserCommission;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\Transaction\PaymentConfirmation;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use Illuminate\Support\Str;
use App\Services\UploadService;
use App\Mail\TransactionMail;
use Illuminate\Support\Facades\Mail;
use DB;

class TransactionRepository extends BaseRepository implements TransactionInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/payment_confirmation';


    public function __construct()
    {
        $this->model = new Transaction();
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $transactions = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->orderBy('created_at', 'DESC')->simplePaginate($limit);
        return $transactions;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $transactions = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        if(isset($params->item_type) && !empty($params->item_type)) $transactions->where('item_type', $params->item_type);
        if(isset($params->payment_method) && !empty($params->payment_method)) $transactions->where('payment_method', $params->payment_method);
        if(isset($params->transaction_status) && !empty($params->transaction_status)) $transactions->where('transaction_status', $params->transaction_status);
        if(isset($params->start_date) && !empty($params->start_date) && isset($params->end_date) && !empty($params->end_date)) $transactions->whereBetween(DB::raw('DATE(created_at)'), [$params->start_date, $params->end_date]);
        $transactions = $transactions->with(['user'])->orderBy('created_at', 'DESC')->paginate($limit);

        $transactions->appends([
            'search' => $params->search,
            'transaction_status' => $params->transaction_status,
            'item_type' => $params->item_type,
            'payment_method' => $params->payment_method,
            'start_date' => $params->start_date,
            'end_date' => $params->end_date,
        ]);

        return $transactions;
    }

    public function getTransactionAccountBalanceByUser($limit = 10)
    {
        $transactions = $this->model->where('user_id', Auth::user()->id)
            ->where(function ($query) {
                $query->where('item_type', 'TopupBalance')
                    ->orWhere('item_type', 'App\Models\Transaction\VoucherCode');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return $transactions;
    }

    public function getTransactionMonthly($limit = 10)
    {
        $transactions = $this->model->whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->with(['user'])->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function find($id)
    {
        return $this->model->with([
            'paymentConfirmation', 
            'paymentConfirmation.bank', 
            'category', 
            'user', 
            'user.student', 
            'user.student.province', 
            'user.student.city', 
            'user.student.district', 
            'user.student.village', 
        ])->find($id);
    }

    public function getAllPaginatedWithParamsByUser($params, $limit = 10)
    {
        $transactions = $this->model;
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function getAllVoucherActivatedPaginatedWithParamsByUser($params, $limit = 10)
    {
        $transactions = $this->model;
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->where('voucher_activated', 1)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function getSummaryTransactionByUser($limit = 5)
    {
        return $this->model
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();
    }

    public function getTotalTransactionToday()
    {
        return $this->model->whereDate('created_at', Carbon::now())->count();
    }

    public function getTotalTransactionMonthly()
    {
        return $this->model->whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->count();
    }

    public function getTotalTransactionYearly()
    {
        return $this->model->whereYear('created_at', Carbon::now()->format('Y'))->count();
    }

    public function getTotalTransactionPending()
    {
        return $this->model->where('transaction_status', 'pending')->count();
    }

    public function getTotalTransactionPaid()
    {
        return $this->model->where('transaction_status', 'paid')->count();
    }

    public function getTotalTransactionDone()
    {
        return $this->model->where('transaction_status', 'done')->count();
    }

    public function getTotalTransactionFailed()
    {
        return $this->model->where('transaction_status', 'failed')->count();
    }

    public function checkTransactionPendingByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'pending'])->first();
    }

    public function getTotalTransactionPendingByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'pending'])->count();
    }

    public function getTotalTransactionPaidByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'paid'])->count();
    }

    public function getTotalTransactionDoneByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'done'])->count();
    }

    public function getTotalTransactionFailedByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'failed'])->count();
    }

    public function getAllWithParams($params)
    {
        $transactions = $this->model->query();

        $transactions = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        if(isset($params->item_type) && !empty($params->item_type)) $transactions->where('item_type', $params->item_type);
        if(isset($params->payment_method) && !empty($params->payment_method)) $transactions->where('payment_method', $params->payment_method);
        if(isset($params->transaction_status) && !empty($params->transaction_status)) $transactions->where('transaction_status', $params->transaction_status);
        if(isset($params->start_date) && !empty($params->start_date) && isset($params->end_date) && !empty($params->end_date)) $transactions->whereBetween(DB::raw('DATE(created_at)'), [$params->start_date, $params->end_date]);

        return $transactions->with(['user', 'category'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
    }

    public function formatPrice($price)
    {
        return "Rp".number_format($price, 0, ",", ".");
    }

    public function update($attributes, $id)
    {
        $input = (array) $attributes;
        $setting = Setting::first();
        $transaction = $this->find($id);
        $input['expired_date'] = $transaction->expired_date ? $transaction->expired_date : ($transaction->period_type == 'day' ? Carbon::now()->addDays($transaction->active_period) : Carbon::now()->addMonths($transaction->active_period));
        $transaction->update($input);

        if ($input['transaction_status'] == "done") {
            if($transaction->item_type == 'TopupBalance') {
                $transaction->user->update(['account_balance' => $transaction->user->account_balance + $transaction->total_payment]);
            } else if($transaction->item_type == 'App\Models\Transaction\Voucher') {
                $memberCategories = $transaction->item->memberCategories;
                if($memberCategories) {
                    $memberCategoryData = $memberCategories->mapWithKeys(function ($category) use ($transaction) {
                        return [$category->id => [
                            'expired_date' => $transaction->expired_date,  
                            'transaction_id' => $transaction->id,    
                            'category_id' => $transaction->category_id,
                        ]];
                    });
    
                    $transaction->user->memberCategories()->attach($memberCategoryData);
    
                    \DB::table('member_category_user')->where('expired_date', '<', Carbon::now())->delete();
                }
            } else {
                UserAccess::create([
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'access_type' => $transaction->item_type,
                    'access_id' => $transaction->item_id,
                ]);
            }
        }

        if($input['transaction_status'] == "done" && $transaction->item_type != 'TopupBalance' && $setting->enable_affiliate_feature == 1) {
            if($transaction->referrer_id && User::find($transaction->referrer_id)) {
                $commissionAmount = $setting->commission_type == 1 ? ($transaction->total_payment * ($setting->commission / 100)) : $setting->commission;

                Commission::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->referrer_id,
                    'amount' => $commissionAmount
                ]);

                $userCommission = UserCommission::firstOrCreate(
                    ['user_id' => $transaction->referrer_id],
                    [
                        'total_commission' => 0,
                        'total_withdrawn' => 0,
                        'current_balance' => 0,
                    ]
                );
        
                $userCommission->increment('total_commission', $commissionAmount);
                $userCommission->increment('current_balance', $commissionAmount);
            }
        }
    
        if ($setting?->add_transaction_notification && $setting->{'transaction_'.$transaction->transaction_status.'_notification_is_active'} == 1) {
            try {
                if ($setting->notification_type == 1 && $transaction->user->student->phone_number) {
                    sendWhatsappNotification($transaction->user->student->phone_number, createTransactionMessage($transaction->user, $transaction));
                } elseif($setting->notification_type == 2 && $transaction->user->email) {
                    Mail::to($transaction->user->email)->send(new TransactionMail($transaction->user, $transaction));
                }
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim notifikasi transaksi: ' . $e->getMessage());
            }
        }

        return $transaction;
    }

    public function paymentConfirmation($transactionId, $attributes)
    {
        $input = $attributes->validated();
        $input['transaction_id'] = $transactionId;

        if($attributes->hasFile('file')){
            $file = $attributes->file('file')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('file'), $this->image_path);
            $input['file'] = $filename;
        }

        $this->model->find($transactionId)->update(['transaction_status' => 'paid']);

        return PaymentConfirmation::create($input);
    }

    public function delete($id)
    {
        $transaction = $this->model->find($id);
        if($transaction->paymentConfirmation) {
            (new UploadService())->deleteFile($transaction->paymentConfirmation->file, $this->image_path);
        }
        return $transaction->delete();
    }
}
