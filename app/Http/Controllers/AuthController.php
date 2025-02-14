<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\MasterData\Student;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivationMail;
use Carbon\Carbon;
use Auth;
use Config;
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
            return redirect()->intended(( $user->level == 1 ? 'admin' : 'user').'/dashboard');
        }

        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $loginTypes = collect($this->setting->login_type)->pluck('type');

        $query = User::query();

        if ($loginTypes->contains('email')) {
            $query->orWhere('email', $request->email);
        }
        if ($loginTypes->contains('username')) {
            $query->orWhere('username', $request->email);
        }
        if ($loginTypes->contains('code')) {
            $query->orWhere('code', $request->email);
        }

        $user = $query->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->level == 2 && $this->setting->device_login_limit && Auth::user()->activeSessionsCount() >= $this->setting->device_login_limit) {
                Auth::logout();
                session()->flash('error', 'Akun Anda telah mencapai batas login. Hanya diperbolehkan login maksimal di '.$this->setting->device_login_limit.' perangkat. Harap logout dari perangkat lain untuk melanjutkan atau hubungi admin');
                return redirect()->back();
            }

            if ($user->level == 1) {
                return redirect()->intended('admin/dashboard');
            } else {
                return redirect()->intended('user/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Data user tidak cocok atau tidak ditemukan.',
        ]);
    }

    public function showRegistrationForm()
    {
        if($this->setting->add_user_registration == 0) {
            return abort('403');                
        }

        $referrer = session('referrer_id') ? User::with('referralLink')->find(session('referrer_id')) : null;

        return inertia('Auth/Register')->with([
            'referrer' => $referrer
        ]);
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $referrer_id = $this->setting->enable_affiliate_feature == 1 && session('referrer_id') 
                && User::find(session('referrer_id')) 
                ? User::find(session('referrer_id'))->id 
                : null;

            $user = User::create([
                'id' => Uuid::uuid4()->getHex(),
                'code' => $request->code,
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'level' => 2,
                'member_type' => $this->setting?->registration_member_type == 1 ? 1 : 2,
                'expiry_verification_date' => Carbon::now()->addDays(1),
                'is_active' => $this->setting?->add_activation_user == 0 ? 1 : 0,
                'referrer_id' => $referrer_id,
            ]);

            Student::create([
                'id' => Uuid::uuid4()->getHex(),
                'user_id' => $user->id,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'is_member' => 0,
            ]);

            DB::commit();

            if ($this->setting?->add_activation_user == 1 && $this->setting->account_activation_notification_is_active == 1) {
                try {
                    if ($this->setting->notification_type == 1 && !empty($user->student->phone_number)) {
                        sendWhatsappNotification($user->student->phone_number, createActivationUserMessage($user, 'activation'));
                    } elseif ($this->setting->notification_type == 2 && !empty($user->email)) {
                        Mail::to($user->email)->send(new UserActivationMail($user, 'activation'));
                    }                    
                    
                    session()->flash('success', 'Akun anda berhasil didaftarkan, silakan cek ' .  ($this->setting->notification_type == 1 ? 'WhatsApp' : 'Email') . ' untuk aktivasi.');

                } catch (\Exception $mailException) {
                    \Log::error('Gagal mengirim email/WhatsApp: ' . $mailException->getMessage());
                    session()->flash('warning', 'Akun berhasil didaftarkan, tetapi notifikasi gagal dikirim. Silakan hubungi admin untuk melakukan aktivasi.');
                }
            } else {
                session()->flash('success', 'Akun berhasil didaftarkan, silakan login.');
            }
            

            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Registrasi gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
