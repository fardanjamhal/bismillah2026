<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Models\Setting\Setting;
use Carbon\Carbon;
use Auth;
use Hash;

class AuthController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->setting = Setting::first();
    }
    public function showLoginForm()
    {
        $user = Auth::user();

        if($user) {        
            if($user->level == 2) {
                Auth::logout();
            } else {
                return redirect()->intended('/admin/dashboard');
            }
        }

        return Inertia::render('Admin/Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
    
        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);

            if($this->setting->single_login == 1) {
                Auth::logoutOtherDevices($request->password);
            }

            if($user->level == 1) {         
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Data User / Peserta Tidak Bisa Login Di Halaman Ini',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Data user tidak cocok atau tidak ditemukan.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route($this->setting->public_access == 0 ? 'admin.login' : 'login');
    }
}
