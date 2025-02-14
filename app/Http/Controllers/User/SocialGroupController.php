<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Setting\SocialGroupRepository;
use App\Models\Setting\Setting;

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
    public function index()
    {
        $setting = Setting::first();

        if($setting && $setting->social_group_mode == 0) {
            return abort('403');
        }

        return inertia('User/SocialGroup/Index', [
            'socialGroups' => $this->socialGroupRepository->getlAll()
        ]);
    }
}
