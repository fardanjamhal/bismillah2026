<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Setting\SocialGroupRepository;
use App\Http\Requests\Setting\SocialGroupRequest;
use Session;

class SocialGroupController extends Controller
{
    protected $socialGroupRepository;

    public function __construct(SocialGroupRepository $socialGroupRepository)
    {
        $this->socialGroupRepository = $socialGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringSocialGroups', $request->getQueryString());

        return inertia('Admin/Setting/SocialGroup/Index', [
            'socialGroups' => $this->socialGroupRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Setting/SocialGroup/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SocialGroupRequest $request)
    {
        try {
            $this->socialGroupRepository->create($request);

            return redirect()->route('admin.social-groups.index');

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
        if(!$this->socialGroupRepository->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Setting/SocialGroup/Edit', [
            'socialGroup' => $this->socialGroupRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SocialGroupRequest $request, $id)
    {
        try {
            if(!$this->socialGroupRepository->find($id)) return abort('404', 'uppss....');

            $this->socialGroupRepository->update($request, $id);

            $queryString = Session::get('queryStringSocialGroups');
            return redirect(route('admin.social-groups.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->socialGroupRepository->delete($id);

            return redirect()->route('admin.social-groups.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            
            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            if(!$this->socialGroupRepository->find($id)) return abort('404', 'uppss....');

            $this->socialGroupRepository->find($id)->update(['is_active' => $request->is_active]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
