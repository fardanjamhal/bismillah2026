<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Material\VideoModuleRepository;
use App\Repositories\Material\DetailVideoModuleRepository;
use App\Models\Material\Lesson;
use App\Http\Requests\Material\DetailVideoModuleRequest;
use Session;

class DetailVideoModuleController extends Controller
{
    protected $detailVideoModuleRepository;
    protected $videoModuleRepository;

    public function __construct(DetailVideoModuleRepository $detailVideoModuleRepository, VideoModuleRepository $videoModuleRepository)
    {
        $this->detailVideoModuleRepository = $detailVideoModuleRepository;
        $this->videoModuleRepository = $videoModuleRepository;
    }

    private function findVideoModuleOrFail($videoModuleId)
    {
        $module = $this->videoModuleRepository->find($videoModuleId);
        if (!$module) {
            abort(404, 'uppss....');
        }
        return $module;
    }

    private function findDetailVideoModuleOrFail($id)
    {
        $detailVideoModule = $this->detailVideoModuleRepository->find($id);
        if (!$detailVideoModule) {
            abort(404, 'uppss....');
        }
        return $detailVideoModule;
    }

    public function index($videoModuleId, Request $request)
    {
        $this->findVideoModuleOrFail($videoModuleId);
        Session::put('queryStringDetailVideoModules', $request->getQueryString());

        return inertia('Admin/Material/DetailVideoModule/Index', [
            'module' => $this->videoModuleRepository->find($videoModuleId),
            'detailVideoModules' => $this->detailVideoModuleRepository->getAllBymodulePaginatedWithParams($videoModuleId, $request)
        ]);
    }

    public function create($videoModuleId)
    {
        $videoModule = $this->findVideoModuleOrFail($videoModuleId);

        return inertia('Admin/Material/DetailVideoModule/Create', [
            'videoModule' => $videoModule,
        ]);
    }

    public function store($videoModuleId, DetailVideoModuleRequest $request)
    {
        try {
            $this->findVideoModuleOrFail($videoModuleId);
            $this->detailVideoModuleRepository->create($request->all());

            return redirect()->route('admin.video-modules.detail-video-modules.index', $videoModuleId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function edit($videoModuleId, $id)
    {
        $videoModule = $this->findVideoModuleOrFail($videoModuleId);
        $detailVideoModule = $this->findDetailVideoModuleOrFail($id);

        return inertia('Admin/Material/DetailVideoModule/Edit', [
            'videoModule' => $videoModule,
            'detailVideoModule' => $detailVideoModule,
        ]);
    }

    public function update($videoModuleId, DetailVideoModuleRequest $request, $id)
    {
        try {
            $this->findVideoModuleOrFail($videoModuleId);
            $this->findDetailVideoModuleOrFail($id);

            $this->detailVideoModuleRepository->update($request->all(), $id);

            return $this->redirectWithQueryString($videoModuleId);
        } catch(\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroy($videoModuleId, $id)
    {
        try {
            $this->findVideoModuleOrFail($videoModuleId);
            $this->findDetailVideoModuleOrFail($id);

            $this->detailVideoModuleRepository->delete($id);

            return redirect()->route('admin.video-modules.detail-video-modules.index', $videoModuleId);
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

    private function redirectWithQueryString($videoModuleId)
    {
        $queryString = Session::get('queryStringDetailVideoModules');
        return redirect(route('admin.video-modules.detail-video-modules.index', $videoModuleId) . (!empty($queryString) ? '?'.$queryString : ''));
    }
}
