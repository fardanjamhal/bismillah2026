<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material\Module;
use App\Repositories\Material\ModuleRepository;
use App\Http\Requests\Material\ModuleRequest;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\User\MemberCategoryRepository;
use Session;

class ModuleController extends Controller
{
    protected $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringModules', $request->getQueryString());

        return inertia('Admin/Material/Module/Index', [
            'modules' => $this->moduleRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Material/Module/Create', [
            'categories' => (new CategoryRepository())->all(),
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        try {
            $this->moduleRepository->create($request);

            return redirect()->route('admin.modules.index');

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
        $module = Module::with([
            'memberCategories' => function ($query) {
                $query->select('member_categories.id', 'member_categories.name');
            }
        ])->find($id);

        $memberCategorySelected = $module->memberCategories;

        if(!$module) return abort('404', 'uppss....');

        return inertia('Admin/Material/Module/Edit', [
            'module' => $module,
            'categories' => (new CategoryRepository())->all(),
            'memberCategorySelected' => $memberCategorySelected,
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request, $id)
    {
        try {
            if(!$this->moduleRepository->find($id)) return abort('404', 'uppss....');

            $this->moduleRepository->update($request, $id);

            $queryString = Session::get('queryStringModules');
            return redirect(route('admin.modules.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->moduleRepository->delete($id);
            
            return redirect()->route('admin.modules.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $module = $this->moduleRepository->find($id);

            if(!$module) return abort('404', 'uppss....');

            $this->moduleRepository->find($id)->update(['status' => $request->status]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
