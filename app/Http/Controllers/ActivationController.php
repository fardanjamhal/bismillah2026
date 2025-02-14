<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterData\Student;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class ActivationController extends Controller
{
    public function activation($userId)
    {
        $user = User::find($userId);
        if(!$user) {
            return redirect('login')->with('error', 'Akun tersebut tidak terdaftar dalam sistem.');
        }

        return inertia('Auth/Activation', [
            'user' => $user,
        ]);
    }

    public function actived($userId)
    {
        $user = User::find($userId);

        if(!$user) {
            return redirect('login')->with('error', 'Akun tersebut tidak terdaftar dalam sistem.');
        }

        if($user->is_active == 1) {
            return redirect('login')->with('success', 'Akun '.$user->email.' sudah aktif, silakan login.');
        }

        if($user->expiry_verification_date < Carbon::now()) {
            return redirect('login')->with('error', 'Link verifikasi sudah kedaluarsa, Silakan hubungi admin.');
        }
        
        $user->update(['is_active' => 1, 'expiry_verification_date' => Carbon::now()]);
        return redirect('login')->with('success', 'Akun dengan email '.$user->email.' telah diaktifkan, silakan login.');
    }

    public function forgotPassword()
    {
        return inertia('Auth/ForgotPassword');
    }

    public function storeForgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $setting = Setting::first();
            $identify = $setting->notification_type == 1 
                ? Student::where('phone_number', $request->contact)->first() 
                : User::where('email', $request->contact)->first();

            if (!$identify) {
                return redirect()->back()->with('error', 'Kontak <b>'.$request->contact.'</b> tidak ditemukan. Silakan hubungi admin.');
            }

            $user = User::findOrFail($setting->notification_type == 1 ? $identify->user_id : $identify->id);

            if ($user->is_active == 0) {
                return redirect()->back()->with('error', 'Untuk melakukan reset password, akun Anda harus aktif terlebih dahulu. Silakan hubungi admin untuk mendapatkan link aktivasi.');
            }

            if ($setting->notification_type == 1) {
                $namePassword = explode(" ", $user->name);
                $newPassword = strtolower($namePassword[0]) . mt_rand(10000, 99999);
                
                $message = "*Mohon dibaca dan dipahami!*\n\n"
                    . "_Hallo, saya admin dari " . strtoupper($setting->app_name ?? "isi data setting terlebih dahulu") . ", berikut permintaan perubahan password untuk akun Anda._\n\n"
                    . "Nama: " . $user->name . "\n"
                    . "Email: " . $user->email . "\n"
                    . "Password Baru: " . $newPassword . "\n\n"
                    . "*Mohon diperhatikan untuk penulisan email dan password baik itu huruf besar atau kecilnya, karena itu sangat berpengaruh untuk login. Ingat dan simpan supaya tidak lupa.*\n\n"
                    . "_Terima kasih_";

                if (!empty($user->student?->phone_number)) {
                    sendWhatsappNotification($user->student->phone_number, $message);
                }

                $user->update(['password' => bcrypt($newPassword)]);
            } else {
                $status = Password::sendResetLink(['email' => $request->contact]);

                if ($status !== Password::RESET_LINK_SENT) {
                    return redirect()->back()->with('error', [__($status)]);
                }
            }

            return redirect()->route('login')->with('success', 'Kami telah mengirimkan perubahan password ke ' 
                . ($setting->notification_type == 1 ? 'Nomor' : 'Email') . ' <b>' . $request->contact . '</b>. Silakan periksa, terima kasih.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses permintaan Anda. Silakan coba lagi atau hubungi admin.' . $e->getMessage());
        }
    }

    public function resetPassword()
    {
        return inertia('Auth/ResetPassword');
    }

    public function storeResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Data berhasil diperbarui. Silakan login menggunakan password baru.')
                : redirect()->back()->with('error', [__($status)]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi.' . $e->getMessage());
        }
    }

}
