<?php

namespace App\Http\Controllers\User\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Material\VideoModuleRepository;
use App\Repositories\Material\DetailVideoModuleRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\MemberCategoryUser;
use App\Models\Setting\Setting;
use Auth;
use Carbon\Carbon;

class VideoModuleController extends Controller
{
    protected $videoModuleRepository, $detailVideoModuleRepository;

    public function __construct(VideoModuleRepository $videoModuleRepository, DetailVideoModuleRepository $detailVideoModuleRepository)
    {
        $this->videoModuleRepository = $videoModuleRepository;
        $this->detailVideoModuleRepository = $detailVideoModuleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('User/Material/VideoModule/Index', [
            'categoryVideoModules' => (new CategoryRepository())->getCategoryVideoModules(),
            'userMemberCategories' => MemberCategoryUser::where('user_id', Auth::user()->id)->where('expired_date', '>', Carbon::now())->get()
        ]);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $videoModule = $this->videoModuleRepository->find($id);

        return inertia('User/Material/VideoModule/Show', [
            'videoModule' => $videoModule,
            'userMemberCategories' => MemberCategoryUser::where('user_id', Auth::user()->id)->where('expired_date', '>', Carbon::now())->get()
        ]);
    }

    public function detailVideoModule($videoModuleId, $detailVideoModuleId)
    {
        $setting = Setting::first();

        $videoModule = $this->videoModuleRepository->find($videoModuleId);
        $detailVideoModule = $this->detailVideoModuleRepository->find($detailVideoModuleId);

        if(!$videoModule || !$detailVideoModule) {
            return abort('404');
        }

        if(Auth::user()->member_type == 2 && $setting->enable_video_module_sales == 1) {

            $checkAcess = false;

            $checkMemberCategories = $this->checkMemberCategories(
                MemberCategoryUser::where([
                    'category_id' => $videoModule->category_id, 
                    'user_id' => Auth::user()->id
                ])
                ->where('expired_date', '>', Carbon::now())
                ->pluck('member_category_id'), 
                $videoModule->memberCategories->pluck('id')->toArray() // Mengonversi koleksi menjadi array
            );

            if($setting->video_module_sales_type == 1 && count($videoModule->userAccess) > 0) {
                $checkAcess = true;
            }

            if($setting->video_module_sales_type == 2 && $checkMemberCategories == true) {
                $checkAcess = true;
            }

            if($setting->video_module_sales_type == 3 && (count($videoModule->userAccess) > 0 || $checkMemberCategories == true)) {
                $checkAcess = true;
            }

            if($checkAcess === false) {
                session()->flash('error', 'Anda tidak memiliki akses ke modul atau materi ini. Silakan melakukan transaksi terlebih dahulu.');
                return redirect()->back();
            }
        }

        return inertia('User/Material/VideoModule/DetailVideoModule', [
            'videoModule' => $videoModule,
            'detailVideoModule' => $detailVideoModule,
        ]);
    }

    function checkMemberCategories($data, $categories) 
    {
        if ($categories && count($categories) > 0) {
            $memberCategoryIds = $data->toArray(); 
            $memberCategoryIds = $memberCategoryIds; 

            return count(array_intersect($memberCategoryIds, $categories)) > 0;
        } else {
            return true;
        }
    }
}
