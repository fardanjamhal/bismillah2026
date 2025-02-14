<?php

namespace App\Http\Controllers\User\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\BankRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\Material\ModuleRepository;
use App\Repositories\Material\VideoModuleRepository;
use App\Repositories\Transaction\VoucherRepository;
use App\Http\Requests\Transaction\PaymentConfirmationRequest;
use App\Http\Requests\User\PaymentMethodRequest;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\PaymentMethod;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionMail;
use Auth;
use Midtrans\Snap;
use Illuminate\Support\Str;
use DB;

class TransactionController extends Controller
{
    public function __construct(
        TransactionRepository $transactionRepository, 
        ExamRepository $examRepository, 
        ExamGroupRepository $examGroupRepository, 
        ModuleRepository $moduleRepository, 
        VideoModuleRepository $videoModuleRepository, 
        VoucherRepository $voucherRepository
        )
    {
        $this->transactionRepository = $transactionRepository;
        $this->examRepository = $examRepository;
        $this->examGroupRepository = $examGroupRepository;
        $this->moduleRepository = $moduleRepository;
        $this->videoModuleRepository = $videoModuleRepository;
        $this->voucherRepository = $voucherRepository;
        $this->setting = Setting::first();

        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Transaction/Transaction/Index', [
            'transactions' => $this->transactionRepository->getAllPaginatedWithParamsByUser($request)
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
        $transaction = $this->transactionRepository->find($id);
        
        if(!$transaction) return abort('404', 'uppss....');
        
        return inertia('User/Transaction/Transaction/Show', [
            'transaction' => $transaction,
            'banks' => (new BankRepository())->all()
        ]);
    }

    public function paymentConfirmation($transactionId)
    {
        $transaction = $this->transactionRepository->find($transactionId);
        
        $type = [
            'App\Models\Transaction\Voucher' => 'Voucher',
            'App\Models\Exam\Exam' => 'Latihan Soal',
            'App\Models\Exam\ExamGroup' => 'Tryout',
            'App\Models\Material\Module' => 'Modul / Materi',
            'App\Models\Material\VideoModule' => 'Video Pembelajaran',
            'App\Models\Course\Course' => 'Course',
            'TopupBalance' => 'Top Up Saldo',
        ];

        if(!$transaction) return abort('404', 'uppss....');

        if($transaction->payment_method != 'manual_transfer') {
            session()->flash('error', 'Anda tidak bisa mengakses halaman tersebut, karena metode tidak sesuai atau anda telah melakukan konfirmasi pembayaran');
            return redirect()->route('user.transactions.show', $transactionId);
        }

        return inertia('User/Transaction/Transaction/PaymentConfirm', [
            'transaction' => $transaction,
            'type' => $type[$transaction->item_type],
            'banks' => (new BankRepository())->all()
        ]);
    }

    public function storePaymentConfirmation(PaymentConfirmationRequest $request, $transactionId)
    {
        try {
            $transaction = $this->transactionRepository->find($transactionId);
            if (!$transaction) {
                abort(404, 'uppss....');
            }
    
            $this->transactionRepository->paymentConfirmation($transactionId, $request);
    
            $transaction = $this->transactionRepository->find($transactionId);
            
            session()->flash('success', 'Konfirmasi pembayaran berhasil. Silakan tunggu hingga admin melakukan persetujuan pada transaksi anda dengan kode ' . $transaction->code);
    
            if ($this->setting?->add_transaction_notification && $this->setting->{'transaction_'.$transaction->transaction_status.'_notification_is_active'} == 1) {
                try {
                    if ($this->setting->notification_type == 1 && $transaction->user->student->phone_number) {
                        sendWhatsappNotification($transaction->user->student->phone_number, createTransactionMessage($transaction->user, $transaction));
                    } elseif($this->setting->notification_type == 2 && $transaction->user->email) {
                        Mail::to($transaction->user->email)->send(new TransactionMail($transaction->user, $transaction));
                    }
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim notifikasi transaksi: ' . $e->getMessage());
                }
            }
    
            return redirect()->route('user.transactions.show', $transactionId);
    
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function buy(Request $request, $type, $id)
    {
        $typeTranslation = [
            'exam' => 'Latihan Soal',
            'examGroup' => 'Tryout',
            'module' => 'Modul/Materi',
            'videoModule' => 'Video Pembelajaran',
            'course' => 'Course',
            'voucher' => 'Voucher',
            'bundling' => 'Paket Bundel',
            'topupBalance' => 'Topup Saldo',
        ];

        if (!array_key_exists($type, $typeTranslation)) {
            return abort(403);
        }

        if ($type == 'topupBalance') {
            $purchase = new \stdClass();
            $purchase->title = 'Top Up Saldo Rp ' . number_format($id, 2, ",", ".");
            $purchase->price_after_discount = $id;
        } else {
            $purchase = $this->{$type . 'Repository'}->find($id);
            if(!$purchase) return abort('404', 'uppss....');
        }
    
        return inertia('User/Transaction/Transaction/Buy', [
            'purchase' => $purchase ?? null,
            'type' => $type,
            'paymentMethods' => PaymentMethod::all(),
            'typeTranslation' => $typeTranslation[$type],
            'urlBack' => $request->headers->get('referer'),
        ]);
    }


    public function buyStore(PaymentMethodRequest $request, $type, $id)
    {
        DB::beginTransaction();

        try {
            $typeTranslation = [
                'exam' => \App\Models\Exam\Exam::class,
                'examGroup' => \App\Models\Exam\ExamGroup::class,
                'module' => \App\Models\Material\Module::class,
                'videoModule' => \App\Models\Material\VideoModule::class,
                'course' => \App\Models\Course\Course::class,
                'voucher' => \App\Models\Transaction\Voucher::class,
                'topupBalance' => 'TopupBalance',
            ];

            if (!array_key_exists($type, $typeTranslation)) {
                return abort(403);
            }

            if ($type == 'topupBalance') {
                $purchase = new \stdClass();
                $purchase->title = 'Top Up Saldo Rp.' . number_format($request->price, 2, ",", ".");
                $purchase->price_after_discount = $request->price;
            } else {
                $purchase = $this->{$type . 'Repository'}->find($id);
                if (!$purchase) {
                    return abort(404, 'Uppss....');
                }
            }

            if ($request->payment_method == 'account_balance' && $purchase->price_after_discount > Auth::user()->account_balance) {
                DB::rollback();
                session()->flash('error', 'Saldo Anda Tidak Cukup Untuk Membeli, silakan Top Up saldo terlebih dahulu.');
                return redirect()->back();
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'referrer_id' => Auth::user()->referrer_id,
                'category_id' => $purchase->category_id ?? null,
                'item_type' => $typeTranslation[$type],
                'item_id' => $purchase->id ?? null,
                'code' => Transaction::generateCode(),
                'description' => ($type != 'topupBalance' ? 'Pembelian ' : '') . $purchase->title,
                'payment_method' => $request->payment_method,
                'active_period' => $purchase->active_period ?? null,
                'period_type' => $purchase->period_type ?? null,
                'total_payment' => $purchase->price_after_discount,
                'transaction_status' => 'pending',
            ]);

            if ($this->setting?->add_transaction_notification && $this->setting->{'transaction_'.$transaction->transaction_status.'_notification_is_active'} == 1) {
                try {
                    if ($this->setting->notification_type == 1 && $transaction->user->student->phone_number) {
                        sendWhatsappNotification($transaction->user->student->phone_number, createTransactionMessage($transaction->user, $transaction));
                    } elseif($this->setting->notification_type == 2 && $transaction->user->email) {
                        Mail::to($transaction->user->email)->send(new TransactionMail($transaction->user, $transaction));
                    }
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim notifikasi transaksi: ' . $e->getMessage());
                }
            }

            if ($request->payment_method == 'automatic_transfer_midtrans') {
                $snapToken = DB::transaction(function () use ($transaction) {
                    $payload = [
                        'transaction_details' => [
                            'order_id' => $transaction->id,
                            'gross_amount' => $transaction->total_payment
                        ],
                        'customer_details' => [
                            'first_name' => $transaction->user->name,
                            'email' => $transaction->user->email,
                            'phone' => $transaction->user && $transaction->user->student ? $transaction->user->student->phone_number : '',
                        ],
                        'item_details' => [
                            [
                                'id' => $transaction->id,
                                'price' => $transaction->total_payment,
                                'quantity' => 1,
                                'name' => Str::limit($transaction->description, 40)
                            ]
                        ]
                    ];

                    $snapToken = Snap::getSnapToken($payload);
                    $transaction->snap_token = $snapToken;
                    $transaction->save();
                    return $this->response['snapToken'] = $snapToken;
                });
            } elseif ($request->payment_method == 'account_balance') {
                (new TransactionRepository())->update((object)["transaction_status" => "done"], $transaction->id);
                Auth::user()->decrement('account_balance', $transaction->total_payment);
                session()->flash('success', 'Pembelian menggunakan saldo berhasil.');
            } else {
                session()->flash('success', 'Silakan lakukan pembayaran.');
            }

            DB::commit();
            return redirect()->route('user.transactions.show', $transaction->id);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function transactionSuccess()
    {
        return inertia('User/Transaction/Transaction/TransactionSuccess');
    }
}
