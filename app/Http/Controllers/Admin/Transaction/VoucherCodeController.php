<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Transaction\VoucherCodeRepository;
use App\Http\Requests\Transaction\VoucherCodeRequest;
use Session;

class VoucherCodeController extends Controller
{
    protected $voucherCodeRepository;

    public function __construct(VoucherCodeRepository $voucherCodeRepository)
    {
        $this->voucherCodeRepository = $voucherCodeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringVoucherCodes', $request->getQueryString());

        return inertia('Admin/Transaction/VoucherCode/Index', [
            'voucherCodes' => $this->voucherCodeRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Transaction/VoucherCode/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherCodeRequest $request)
    {
        try {
            $this->voucherCodeRepository->create($request->all());

            return redirect()->route('admin.voucher-codes.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->voucherCodeRepository->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/VoucherCode/Edit', [
            'voucherCode' => $this->voucherCodeRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherCodeRequest $request, $id)
    {
        if(!$this->voucherCodeRepository->find($id)) return abort('404', 'uppss....');

        $this->voucherCodeRepository->update($request->except(['_token']), $id);

        $queryString = Session::get('queryStringVoucherCodes');
        return redirect(route('admin.voucher-codes.index') . (!empty($queryString) ? '?'.$queryString : ''));

        try {
            if(!$this->voucherCodeRepository->find($id)) return abort('404', 'uppss....');

            $this->voucherCodeRepository->update($request, $id);

            return redirect()->route('admin.voucher-codes.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

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
            $this->voucherCodeRepository->delete($id);

            return redirect()->route('admin.voucher-codes.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
