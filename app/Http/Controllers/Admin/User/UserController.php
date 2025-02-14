<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository as UserRepository;
use App\Http\Requests\User\UserRequest;
use App\Models\Setting\Setting;
use App\Models\User;
use App\Models\MemberCategoryUser;
use App\Models\UserAccess;
use Carbon\Carbon;
use App\Repositories\Exam\GradeRepository;
use App\Models\Exam\ExamGroupUser;
use App\Imports\UserImport;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\UserActivationMail;
use Illuminate\Support\Facades\Mail;
use Session;
use DB;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::put('queryStringUsers', $request->getQueryString());
        
        return inertia('Admin/User/User/Index', [
            'users' => $this->userRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/User/User/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userRepository->create($request);
            return redirect()->route('admin.users.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        $setting = Setting::first();

        $histories =  ExamGroupUser::with([
            'examGroup',
            'examGroup.lessonCategory',
            'examGroup.category',
        ])
        ->where(['user_id' => $id])
        ->orderBy('created_at', 'DESC')->get();

        $loginSessions = DB::table('sessions')->where('user_id', $user->id)->get();

        return inertia('Admin/User/User/Show', [
            'user' => $user,
            'histories' => $histories,
            'loginSessions' => $loginSessions,
            'grades' => (new GradeRepository())->getAllByUserWithParams(['user_id' => $id]),
            'verificationLink' => urldecode(($setting->app_url ?? 'Atur Setting Terlebih Dahulu').'/user/'.$user->id.'/activation')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return inertia('Admin/User/User/Edit', [
            'user' => $this->userRepository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $this->userRepository->update($request, $id);

            $queryString = Session::get('queryStringUsers');
            return redirect(route('admin.users.index') . (!empty($queryString) ? '?'.$queryString : ''));

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
            $this->userRepository->delete($id);
            return redirect()->route('admin.users.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());
            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            if(!$this->userRepository->find($id)) return abort('404', 'uppss....');

            $this->userRepository->find($id)->update(['is_active' => $request->is_active]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function activationReminder($id)
    {
        $user = $this->userRepository->find($id) ?? abort(404, 'Uppss...');

        try {
            $setting = Setting::first();

            if($setting->account_activation_reminder_notification_is_active == 0) {
                return redirect()->back()->with('warning', 'Tidak bisa mengirim reminder karena fitur di nonaktifkan. Silakan aktifkan terlebih dahulu.');
            }

            if ($setting->notification_type == 1 && $setting->account_activation_reminder_notification_is_active == 1 && $user->student?->phone_number) {
                sendWhatsappNotification($user->student->phone_number, createActivationUserMessage($user, 'activation_reminder'));
            } elseif ($setting->notification_type == 2 && $user->email) {
                Mail::to($user->email)->send(new UserActivationMail($user, 'activation_reminder'));
            }

            return redirect()->back()->with('success', 'Reminder telah dikirim.');
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim notifikasi. Silakan coba lagi.');
        }
    }

    public function sendActivationLink($id)
    {
        $user = $this->userRepository->find($id) ?? abort(404, 'Uppss...');

        try {
            $user->update(['expiry_verification_date' => Carbon::now()->addDays(1)]);

            $setting = Setting::first();

            if($setting->account_activation_notification_is_active == 0) {
                return redirect()->back()->with('warning', 'Tidak bisa mengirim link aktivasi karena fitur di nonaktifkan. Silakan aktifkan terlebih dahulu.');
            }

            if ($setting->notification_type == 1 && $setting->account_activation_notification_is_active == 1 && $user->student?->phone_number) {
                sendWhatsappNotification($user->student->phone_number, createActivationUserMessage($user, 'activation'));
            } elseif ($setting->notification_type == 2 && $user->email) {
                Mail::to($user->email)->send(new UserActivationMail($user, 'activation'));
            }

            return redirect()->back()->with('success', 'Link Aktivasi telah dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim link aktivasi. Silakan coba lagi. '.'<pre>'.$e->getMessage().'</pre>');
        }
    }

    public function destroyMemberCategoryUser($userId, $memberCategoryId)
    {
        if(!$this->userRepository->find($userId)) return abort('404', 'uppss....');

        $MemberCategoryUser = MemberCategoryUser::findOrFail($memberCategoryId);
        $MemberCategoryUser->delete();

        return redirect()->back()->with('success', 'Akses Kategori Member Telah Dihapus.');
    }

    public function destroySession($userId, $sessionId)
    {
        DB::table('sessions')->where(['id' => $sessionId, 'user_id' => $userId])->delete();
        return redirect()->back()->with('success', 'Session login/perangkat berhasil dihapus.');
    }

    public function destroyUserAccess($userId, $memberCategoryId)
    {
        if(!$this->userRepository->find($userId)) return abort('404', 'uppss....');

        $userAccess = UserAccess::findOrFail($memberCategoryId);
        $userAccess->delete();

        return redirect()->back()->with('success', 'Akses Latihan Soal atau Tryout Telah Dihapus.');
    }

    public function showImportForm()
    {
        return inertia('Admin/User/User/Import');
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);

            $collection = Excel::toCollection(null, $request->file);
            $headingRow = $collection->first()->first();
            $headingArray = $headingRow->toArray();

            $headerFile = array_filter($headingArray, function ($value) {
                return $value !== null;
            });

            $headerFile = array_values($headerFile);

            $headerImport = [
                'KODE',
                'NAMA',
                'EMAIL',
                'USERNAME',
                'PASSWORD',
                'TIPE_MEMBER',
                'LEVEL',
                'STATUS',
            ];

            if ($headerFile === $headerImport) {
                Excel::import(new UserImport, $request->file('file'));
                return redirect()->route('admin.users.index')->with('success', 'Import Data User Berhasil. ');

            } else {
                session()->flash('error', 'Format Import Tidak Sesuai. ');
                return redirect()->back();
            }

            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
    
            // Map terjemahan
            $translationMap = [
                'Username is required' => 'Username wajib diisi',
                'Username already exists' => 'Username sudah ada',
                'Email is required' => 'Email wajib diisi',
                'Email is not valid' => 'Email tidak valid',
                'Email already exists' => 'Email sudah ada',
                'Kode is required' => 'Kode wajib diisi',
                'Kode already exists' => 'Kode sudah ada',
            ];
    
            $errorMessages = [];
            $rowErrors = [];
            foreach ($failures as $failure) {
                // Terjemahkan pesan kesalahan
                $translatedErrors = array_map(function($error) use ($translationMap) {
                    return $translationMap[$error] ?? $error;
                }, $failure->errors());
    
                // Mendapatkan nilai dari kolom yang menyebabkan kesalahan
                $values = $failure->values();
    
                // Pastikan array values memiliki indeks yang sesuai
                $value1 = isset($values[0]) ? $values[0] : '';
                $value2 = isset($values[1]) ? $values[1] : '';
    
                // Tambahkan pesan kesalahan baris ke dalam array
                $rowErrors[$failure->row()][$value1][] = $translatedErrors[0] ?? '';
                $rowErrors[$failure->row()][$value2][] = $translatedErrors[1] ?? '';
            }
    
            foreach ($rowErrors as $row => $errors) {
                foreach ($errors as $value => $errorArray) {
                    // Gabungkan pesan kesalahan untuk email dan username
                    $errorMessage = 'Ada kesalahan pada baris ke ' . $row . '. ';
                    if (count(array_filter($errorArray)) == 2) {
                        $errorList = array_filter($errorArray);
                        $errorMessage .= implode(' dan ', array_unique($errorList));
                    } else {
                        // Jika hanya satu jenis kesalahan yang terjadi
                        $errorMessage .= implode(' ,', array_unique(array_filter($errorArray)));
                    }
                    $errorMessages[] = $errorMessage;
                }
            }
    
            // Menghapus pesan duplikat
            $errorMessages = array_unique($errorMessages);
    
            // Menggabungkan pesan kesalahan menjadi satu string
            $errorMessage = implode(' ', $errorMessages);
    
            // Redirect dengan pesan kesalahan
            return redirect()->route('admin.users.import_form')->withErrors($errorMessage)->withInput();
        }
    }

    public function export()
    {
        try {
            return Excel::download(new UserExport, 'data-user-'.Carbon::now()->format('d-F-Y').'.xlsx');

        } catch(\Exception $e) {
            return $e;
            #get message
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }
}
