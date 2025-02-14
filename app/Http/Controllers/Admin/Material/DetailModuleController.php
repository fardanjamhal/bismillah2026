<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Material\ModuleRepository;
use App\Repositories\Material\DetailModuleRepository;
use App\Models\Material\Lesson;
use App\Http\Requests\Material\DetailModuleRequest;
use Session;

class DetailModuleController extends Controller
{
    protected $detailModuleRepository;
    protected $moduleRepository;

    public function __construct(DetailModuleRepository $detailModuleRepository, ModuleRepository $moduleRepository)
    {
        $this->detailModuleRepository = $detailModuleRepository;
        $this->moduleRepository = $moduleRepository;
    }

    private function findModuleOrFail($moduleId)
    {
        $module = $this->moduleRepository->find($moduleId);
        if (!$module) {
            abort(404, 'uppss....');
        }
        return $module;
    }

    private function findDetailModuleOrFail($id)
    {
        $detailModule = $this->detailModuleRepository->find($id);
        if (!$detailModule) {
            abort(404, 'uppss....');
        }
        return $detailModule;
    }

    public function index($moduleId, Request $request)
    {
        $this->findModuleOrFail($moduleId);
        Session::put('queryStringDetailModules', $request->getQueryString());

        return inertia('Admin/Material/DetailModule/Index', [
            'module' => $this->moduleRepository->find($moduleId),
            'detailModules' => $this->detailModuleRepository->getAllBymodulePaginatedWithParams($moduleId, $request)
        ]);
    }

    public function create($moduleId)
    {
        $module = $this->findModuleOrFail($moduleId);

        return inertia('Admin/Material/DetailModule/Create', [
            'module' => $module,
        ]);
    }

    public function store($moduleId, DetailModuleRequest $request)
    {
        try {
            $this->findModuleOrFail($moduleId);
            $this->detailModuleRepository->create($request->all());

            return redirect()->route('admin.modules.detail-modules.index', $moduleId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function edit($moduleId, $id)
    {
        $module = $this->findModuleOrFail($moduleId);
        $detailModule = $this->findDetailModuleOrFail($id);

        return inertia('Admin/Material/DetailModule/Edit', [
            'module' => $module,
            'detailModule' => $detailModule,
        ]);
    }

    public function update($moduleId, DetailModuleRequest $request, $id)
    {
        try {
            $this->findModuleOrFail($moduleId);
            $this->findDetailModuleOrFail($id);

            $this->detailModuleRepository->update($request->all(), $id);

            return $this->redirectWithQueryString($moduleId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($moduleId, $id)
    {
        try {
            $this->findModuleOrFail($moduleId);
            $this->findDetailModuleOrFail($id);

            $this->detailModuleRepository->delete($id);

            return redirect()->route('admin.modules.detail-modules.index', $moduleId);
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            return redirect()->back();
        }
    }

    private function handleException(\Exception $e, $request)
    {
        session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
        return redirect()->back()->withInput($request->all());
    }

    private function redirectWithQueryString($moduleId)
    {
        $queryString = Session::get('queryStringDetailModules');
        return redirect(route('admin.modules.detail-modules.index', $moduleId) . (!empty($queryString) ? '?'.$queryString : ''));
    }
}
