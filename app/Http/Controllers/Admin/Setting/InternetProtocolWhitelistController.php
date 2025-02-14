<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Setting\InternetProtocolWhitelistRepository;
use App\Http\Requests\Setting\InternetProtocolWhitelistRequest;
use Session;

class InternetProtocolWhitelistController extends Controller
{
    protected $internetProtocolWhitelistRepository;

    public function __construct(InternetProtocolWhitelistRepository $internetProtocolWhitelistRepository)
    {
        $this->internetProtocolWhitelistRepository = $internetProtocolWhitelistRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringInternetProtocolWhitelists', $request->getQueryString());

        return inertia('Admin/Setting/InternetProtocolWhitelist/Index', [
            'internetProtocolWhitelists' => $this->internetProtocolWhitelistRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Setting/InternetProtocolWhitelist/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InternetProtocolWhitelistRequest $request)
    {
        try {
            $this->internetProtocolWhitelistRepository->create($request->all());

            return redirect()->route('admin.internet-protocol-whitelists.index');

        } catch(\Exception $e) {
            return $e;
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
        if(!$this->internetProtocolWhitelistRepository->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Setting/InternetProtocolWhitelist/Edit', [
            'internetProtocolWhitelist' => $this->internetProtocolWhitelistRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InternetProtocolWhitelistRequest $request, $id)
    {
        try {
            if(!$this->internetProtocolWhitelistRepository->find($id)) return abort('404', 'uppss....');

            $this->internetProtocolWhitelistRepository->update($request->all(), $id);

            $queryString = Session::get('queryStringInternetProtocolWhitelists');
            return redirect(route('admin.internet-protocol-whitelists.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->internetProtocolWhitelistRepository->delete($id);

            return redirect()->route('admin.internet-protocol-whitelists.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
