<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\MemberCategoryRepository;
use App\Http\Requests\User\MemberCategoryRequest;
use Session;

class MemberCategoryController extends Controller
{
    protected $memberCategoryRepository;

    public function __construct(MemberCategoryRepository $memberCategoryRepository)
    {
        $this->memberCategoryRepository = $memberCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringMemberCategories', $request->getQueryString());

        return inertia('Admin/Transaction/MemberCategory/Index', [
            'memberCategories' => $this->memberCategoryRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Transaction/MemberCategory/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberCategoryRequest $request)
    {
        try {
            $this->memberCategoryRepository->create($request->all());

            return redirect()->route('admin.member-categories.index');

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
        if(!$this->memberCategoryRepository->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/MemberCategory/Edit', [
            'memberCategory' => $this->memberCategoryRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MemberCategoryRequest $request, $id)
    {
        try {
            if(!$this->memberCategoryRepository->find($id)) return abort('404', 'uppss....');

            $this->memberCategoryRepository->update($request->all(), $id);

            $queryString = Session::get('queryStringMemberCategories');
            return redirect(route('admin.member-categories.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->memberCategoryRepository->delete($id);

            return redirect()->route('admin.member-categories.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
