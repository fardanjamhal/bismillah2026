<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\PaymentMethodRepository;
use App\Http\Requests\Transaction\PaymentMethodRequest;
use Session;

class PaymentMethodController extends Controller
{
    protected $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringPaymentMethods', $request->getQueryString());

        return inertia('Admin/Transaction/PaymentMethod/Index', [
            'paymentMethods' => $this->paymentMethodRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        if(!$this->paymentMethodRepository->findByCode($code)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/PaymentMethod/Edit', [
            'paymentMethod' => $this->paymentMethodRepository->findByCode($code),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodRequest $request, $code)
    {
        try {
            if (!$this->paymentMethodRepository->findByCode($code)) return abort('404', 'uppss....');

            $this->paymentMethodRepository->update($request->all(), $code);

            $queryString = Session::get('queryStringPaymentMethods');
            return redirect(route('admin.payment-methods.index') . (!empty($queryString) ? '?'.$queryString : ''));

        } catch (\Exception $e) {
            return $e; // Ini seharusnya diubah
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

}
