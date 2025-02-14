<?php

namespace App\Http\Controllers\User\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Affiliate\UserBankRepository;
use App\Http\Requests\Affiliate\UserBankRequest;
use Session;
use Auth;

class UserBankController extends Controller
{
    protected $userBankRepository;

    public function __construct(UserBankRepository $userBankRepository)
    {
        $this->userBankRepository = $userBankRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringUserBanks', $request->getQueryString());

        return inertia('User/Affiliate/Bank/Index', [
            'userBanks' => $this->userBankRepository->getAllByUserPaginatedWithParams(Auth::user()->id, $request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('User/Affiliate/Bank/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserBankRequest $request)
    {
        try {
            $input = $request->all();
            $input['user_id'] = auth()->user()->id;
            $this->userBankRepository->create($input);

            return redirect()->route('user.user-banks.index');

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
        if(!$this->userBankRepository->find($id)) return abort('404', 'uppss....');

        return inertia('User/Affiliate/Bank/Edit', [
            'userBank' => $this->userBankRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserBankRequest $request, $id)
    {
        try {
            if(!$this->userBankRepository->find($id)) return abort('404', 'uppss....');

            $this->userBankRepository->update($request->except(['_token', '_method']), $id);

            $queryString = Session::get('queryStringUserBanks');
            return redirect(route('user.user-banks.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->userBankRepository->delete($id);

            return redirect()->route('user.user-banks.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            
            return redirect()->back()->withInput($request->all());
        }
    }
}
