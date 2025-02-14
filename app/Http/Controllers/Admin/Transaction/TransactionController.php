<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\BankRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Transaction\VoucherRepository;
use App\Repositories\Transaction\PaymentMethodRepository;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Exports\TransactionExport;
use App\Models\User;
use App\Models\Transaction\Transaction;
use Excel;
use Session;
use DB;
use Carbon\Carbon;
use Midtrans\Snap;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $transactionRepository, $examRepository, $examGroupRepository, $voucherRepository;

    public function __construct(TransactionRepository $transactionRepository, ExamRepository $examRepository, ExamGroupRepository $examGroupRepository, VoucherRepository $voucherRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->examRepository = $examRepository;
        $this->examGroupRepository = $examGroupRepository;
        $this->voucherRepository = $voucherRepository;

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
        Session::put('queryStringTransactions', $request->getQueryString());

        return inertia('Admin/Transaction/Transaction/Index', [
            'transactions' => $this->transactionRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users =  User::select(
            'id',
            \DB::raw("CONCAT(name, ' - ', email) as name")
        )->orderBy('created_at', 'ASC')->get();
        
        return inertia('Admin/Transaction/Transaction/Create', [
            'categories' => (new CategoryRepository())->all(),
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        try {
            $user = User::find($request->user_id['id']);

            if(!$user) {
                return redirect()->back()->with('failed', 'Data Peserta Tidak Ditemukan');
            }
            
            $typeModel = [
                \App\Models\Exam\Exam::class,
                \App\Models\Exam\ExamGroup::class,
                \App\Models\Transaction\Voucher::class,
                'TopupBalance',
            ];

            $type = [
                ['item_id' => $request->exam_id, 'name' => 'exam'],
                ['item_id' => $request->exam_group_id, 'name' => 'examGroup'],
                ['item_id' => $request->voucher_id, 'name' => 'voucher'],
                ['item_id' => '', 'name' => 'topupBalance']
            ];

            $purchase = $request->item_type == 3
                ? (object)['title' => 'Top Up Saldo Rp.' . number_format($request->total_payment, 2, ",", "."), 'price_after_discount' => $request->total_payment]
                : $this->{$type[$request->item_type]['name'] . 'Repository'}->find($type[$request->item_type]['item_id']);

            if (!$purchase && $request->item_type != 3) return abort(404, 'Uppss...');

            if ($request->payment_method == 'account_balance' && $request->reduce_account_balance == 1 && $purchase->price_after_discount > $user->account_balance) {
                return redirect()->back()->with('failed', 'Saldo peserta atas nama '.$user->name. ' ('.$user->email.') tidak cukup untuk membeli, silakan top up saldo terlebih dahulu.');
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'category_id' => $purchase->category_id ?? null,
                'item_type' => $typeModel[$request->item_type],
                'item_id' => $purchase->id ?? null,
                'code' => Transaction::generateCode(),
                'description' => ($type[$request->item_type]['name'] != 'topupBalance' ? 'Pembelian ' : '') . $purchase->title,
                'payment_method' => $request->payment_method,
                'active_period' => $purchase->active_period ?? null,
                'period_type' => $purchase->period_type ?? null,
                'total_payment' => $purchase->price_after_discount,
                'created_at' => $request->created_at ? $request->created_at : Carbon::now(),
                'expired_date' => $request->item_type == 2 ? ($request->expired_date ? $request->expired_date : null) : null,
                'transaction_status' => $request->transaction_status,
            ]);

            if ($request->payment_method == 'automatic_transfer_midtrans') {
                DB::transaction(function () use ($transaction) {
                    $payload = [
                        'transaction_details' => ['order_id' => $transaction->id, 'gross_amount' => $transaction->total_payment],
                        'customer_details' => ['first_name' => $transaction->user->name, 'email' => $transaction->user->email],
                        'item_details' => [['id' => $transaction->id, 'price' => $transaction->total_payment, 'quantity' => 1, 'name' => Str::limit($transaction->description, 40)]]
                    ];

                    $transaction->snap_token = Snap::getSnapToken($payload);
                    $transaction->save();
                });
                session()->flash('success', 'Pembelian menggunakan transfer otomatis sudah dibuat.');

            } elseif ($request->payment_method == 'account_balance') {
                if($request->reduce_account_balance == 1) {
                    $user->decrement('account_balance', $transaction->total_payment);
                }
                session()->flash('success', 'Pembelian menggunakan saldo berhasil.');

            } else {
                session()->flash('success', 'Transaksi Berhasil Dibuat.');
            }

            (new TransactionRepository())->update((object)["transaction_status" => $transaction->transaction_status], $transaction->id);
            return redirect()->route('admin.transactions.index');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());
            return redirect()->back()->withInput($request->all());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->transactionRepository->find($id)) return abort('404', 'uppss....');
        
        return inertia('Admin/Transaction/Transaction/Show', [
            'transaction' => $this->transactionRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $transaction = $this->transactionRepository->find($id);

        if (!$transaction) {
            DB::rollBack();
            return abort('404', 'uppss....');
        }

        $this->transactionRepository->update($request->except('_token', '_method'), $id);

        DB::commit();

        $queryString = Session::get('queryStringTransactions');
        return redirect(route('admin.transactions.show', $transaction->id) . (!empty($queryString) ? '?' . $queryString : ''));

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());
        return redirect()->back()->withInput($request->all());
    }
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->transactionRepository->delete($id);

            return redirect()->route('admin.transactions.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            
            return redirect()->back()->withInput($request->all());
        }
    }

    public function invoice($id)
    {
        if(!$this->transactionRepository->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/Transaction/Invoice', [
            'transaction' => $this->transactionRepository->find($id),
            'banks' => (new BankRepository())->all()
        ]);
    }

    public function export(Request $request)
    {
        $transactions = $this->transactionRepository->getAllWithParams($request);
        return Excel::download(new TransactionExport($transactions), 'transactions_'.Carbon::now()->format('d-F-Y_H_i_s').'.xlsx');
    }
}
