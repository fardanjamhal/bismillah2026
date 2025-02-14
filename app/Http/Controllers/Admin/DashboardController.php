<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Exam\ExamGroupRepository;
use App\Repositories\MasterData\AnnouncementRepository;
use App\Repositories\Lesson\QuestionTitleRepository;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\MasterData\Category;

class DashboardController extends Controller
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $chart = new VisitorCounterChart(new LarapexChart);

        return inertia('Admin/Dashboard/Index', [
            'totalStudent' => number_format((new UserRepository())->getTotalStudent()),
            'totalStudentActive' => number_format((new UserRepository())->getTotalStudentActive()),
            'totalStudentNonActive' => number_format((new UserRepository())->getTotalStudentNonActive()),
            'totalStudentMember' => number_format((new UserRepository())->getTotalStudentMember()),

            'totalTransactionToday' => number_format($this->transactionRepository->getTotalTransactionToday()),
            'totalTransactionMonthly' => number_format($this->transactionRepository->getTotalTransactionMonthly()),
            'totalTransactionYearly' => number_format($this->transactionRepository->getTotalTransactionYearly()),

            'totalTransactionPending' => number_format($this->transactionRepository->getTotalTransactionPending()),
            'totalTransactionPaid' => number_format($this->transactionRepository->getTotalTransactionPaid()),
            'totalTransactionDone' => number_format($this->transactionRepository->getTotalTransactionDone()),
            'totalTransactionFailed' => number_format($this->transactionRepository->getTotalTransactionFailed()),

            'totalQuestionTitleByUser' => number_format((new QuestionTitleRepository())->totalQuestionTitleByUser()),
            'totalExamByUser' => number_format((new ExamRepository())->totalExamByUser()),
            'totalExamGroupByUser' => number_format((new ExamGroupRepository())->totalExamGroupByUser()),

            'transactions' => $this->transactionRepository->getTransactionMonthly(),
            'announcementSummaries' =>  (new announcementRepository())->getAnnouncementSummaries(),
            // 'chart' => $chart->build()
            'totalDataInCategories' => Category::withCount(['exam as exam_count' => function ($query) {
                $query->whereNull('exam_group_id');
            }, 'ExamGroup', 'module', 'videoModule', 'course'])
            ->where('development_status', 'production')
            ->orderBy('created_at', 'ASC')
            ->get()     
        ]);
    }

    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('upload')) {
                $file = $request->file('upload');
                $filename =Carbon::now()->format('Ymdhis'). '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('/upload_files/questions', $filename, 'public');
                $url = '/storage/upload_files/questions/' . $filename;

                return response()->json([
                    'uploaded' => true,
                    'url' => $url
                ]);
            } else {
                return response()->json([
                    'uploaded' => false,
                    'error' => [
                        'message' => 'No file was uploaded.'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'File upload failed. Please try again later.'. $e->getMessage()
                ]
            ]);
        }
    }
}
