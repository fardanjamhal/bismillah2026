<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam\Answer;
use App\Models\Exam\Exam;
use App\Models\Lesson\{DetailValueCategory, ValueCategory};
use App\Models\Exam\GradeDetail;
use App\Models\Exam\Grade;
use App\Models\Exam\ExamGroupUser;
use App\Models\Exam\ExamGroup;
use DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid as Generator;
use Carbon\Carbon;
use App\Models\Lesson\Question;
use App\Models\Transaction\Transaction;
use Auth;
use App\Models\Transaction\Voucher;

class TestingController extends Controller
{
    public function checkSlope()
    {
        $dataAnswer = [
            27,
            0,
            26,
            0,
            19,
            0,
            0,
            0,
            0,
            0
        ];
        return $this->slope($dataAnswer);
    }

    function slope($answerData) 
    {
        $n = count($answerData);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $answerData[$i];
            
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);

        return $slope;
    }
    
    public function checkConnection()
    {
        try {
            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function changeTransaction()
    {
        $transactions = Transaction::all();

        foreach($transactions as $transaction) {
            \App\Models\MemberCategoryUser::create([
                'user_id' => $transaction->user_id,
                'member_category_id' => 2,
                'category_id' => $transaction->category_id, 
                'transaction_id' => $transaction->id, 
                'expired_date' => $transaction->expired_date,
            ]);
        }

        return "selesai";
    }

    public function checkConfigMidtrans()
    {
        return [
            'client_key' => config('services.midtrans.clientKey'),
            'server_key' => config('services.midtrans.serverKey'),
            'is_production' => config('services.midtrans.isProduction'),
            'is_sanitized' => config('services.midtrans.isSanitized'),
            'is_3ds' => config('services.midtrans.is3ds'),
        ];
    }

    public function checkIp(Request $request)
    {
        return $request->ip();
    }

    function checkMemberCategories($data, $categories) {
        foreach ($data as $entry) {
            if (count(array_intersect($entry['member_categories'], $categories)) > 0) {
                return true;
            }
        }
        return false;
    }

    public function index()
    {
        return \Config::get('mail.mailers.smtp.host');
        $data = \App\Models\UserMemberCategory::all();

        return $this->checkMemberCategories($data, ["d Member", "test Member", "Hadian Member"]) == true ? 'ada' :'tidak ada';

        $grades = Grade::all();

        foreach($grades as $grade) {
            $answerData = [];
            foreach($grade->answers as $answer) {
                $answerData[] = [
                    'is_correct' => $answer['is_correct'],
                    'answer' => $answer['question_answer'],
                    'value_category_id' => $answer['value_category_id']
                ];
            }

            $grade->update(['answers' => $answerData]);
        }

        return "ada";

        
        
        $grade = DB::table('grades')
            ->join('exams', 'exams.id', '=', 'grades.exam_id')
            ->join('question_titles', 'question_titles.id', '=', 'exams.question_title_id')
            ->where('grades.id', 'c3a78f78-06e9-401c-9357-0dcad00972b9');
        
        $grade->update(['grades.answers' => []]);
        
        $updatedGrade = $grade->first();
        
        return $updatedGrade;
    
    }

    public function getOption($number)
    {
        switch ($number) {
            case 0:
                return 'A';
                break;
            case 1:
                return 'B';
                break;
            case 2:
                return 'C';
                break;
            case 3:
                return 'D';
                break;
            default:
                return 'E';
                break;
        }
    }

    public function localStorage()
    {
        $grade = Grade::first();

        return $grade;
        $questions = array_column($grade->answers, 'question_id');


        $data = [];
        foreach($questions as $question) {
            $data[] = Question::find($question);
        }

        return $data;
    }

    public function onprogress()
    {
        return inertia('OnProgress/OnProgress');
    }

    public function privateAccess(Request $request)
    {
        $setting = \App\Models\Setting\Setting::first();

        if($setting && $setting->public_access == 0) {
            $ipWhitelist =  \App\Models\Setting\InternetProtocolWhitelist::pluck('ip')->toArray();

            if (!in_array($request->ip(), $ipWhitelist)) {
                return inertia('ErrorPage/403');
            }
        }
        
        return redirect()->route('home');
        
    }

    public function testingWa()
    {
        $token = 'HoL+yqdpN9PPmIiog0P7';
        $curl = curl_init();

        $phoneNumber = '081223230946';
        $message = "*Mohon dibaca dan dipahami!*\n\n_Hallo, saya admin dari pelajarin.com, akun anda telah terdaftar di platform kami, berikut link verifikasi untuk aktivasi akun anda._\n\nNama: Dede Rusliandi\nEmail: dede.rusliandi1@gmail.com\n\nBerikut link verifikasi anda\nhttps://pelajarin.com/user/271373d8-0ed8-47a2-837a-e849b96ac0bd/activation \n\n*Jika link tidak bisa diklik, silakan copy dan paste dibrowser anda.*\n\n_terimakasih telah menjadi bagian dari kami, semoga pelajarin.com dapat membantu anda lolos terpilih. aamiin._";
        
        $data = [
            'target' => $phoneNumber,
            'message' => $message
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$token.''
        ),
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    function changeQuestionJson()
    {
        $questions = Question::all();

        foreach ($questions as $question) {
            $answer = $question->answer;

            // Check if $answer is already an array, if not, make it an array
            if (!is_array($answer)) {
                $answer = [$answer];
            }

            // Update the question's answer field
            $question->update([
                'answer' => $answer
            ]);
        }

        return "finish";
    }
}
