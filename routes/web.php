<?php

use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\GradeGeneratorController;

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterData\CategoryController;
use App\Http\Controllers\Admin\MasterData\SubCategoryController;
use App\Http\Controllers\Admin\MasterData\AnnouncementController;
use App\Http\Controllers\Admin\MasterData\FaqController;
use App\Http\Controllers\Admin\MasterData\NewsController;
use App\Http\Controllers\Admin\User\UserController;

use App\Http\Controllers\Admin\Utility\PsychologyCalculatorController;

use App\Http\Controllers\Admin\Lesson\LessonCategoryController;
use App\Http\Controllers\Admin\Lesson\LessonController;
use App\Http\Controllers\Admin\Lesson\ValueCategoryGroupController;
use App\Http\Controllers\Admin\Lesson\ValueCategoryController;

use App\Http\Controllers\Admin\Lesson\DetailValueCategoryController;
use App\Http\Controllers\Admin\Lesson\QuestionTitleController;
use App\Http\Controllers\Admin\Lesson\QuestionController;
use App\Http\Controllers\Admin\Lesson\QuestionTemplateController;

use App\Http\Controllers\Admin\Exam\ExamController;
use App\Http\Controllers\Admin\Exam\ExamGroupController;
use App\Http\Controllers\Admin\Exam\ExamGroupDetailController;

use App\Http\Controllers\Admin\Material\ModuleController;
use App\Http\Controllers\Admin\Material\DetailModuleController;
use App\Http\Controllers\Admin\Material\VideoModuleController;
use App\Http\Controllers\Admin\Material\DetailVideoModuleController;

use App\Http\Controllers\Admin\Course\CourseController;
use App\Http\Controllers\Admin\Course\CourseDetailController;
use App\Http\Controllers\Admin\Course\CourseSectionController;

use App\Http\Controllers\Admin\Transaction\BankController;
use App\Http\Controllers\Admin\Transaction\PaymentMethodController;
use App\Http\Controllers\Admin\Transaction\VoucherController;
use App\Http\Controllers\Admin\Transaction\MemberCategoryController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Controllers\Admin\Transaction\VoucherCodeController;
use App\Http\Controllers\Admin\User\AccountBalanceController;

use App\Http\Controllers\Admin\Affiliate\CommissionController;
use App\Http\Controllers\Admin\Affiliate\WithdrawalController;

use App\Http\Controllers\Admin\Region\RegionController;
use App\Http\Controllers\Admin\Setting\InternetProtocolWhitelistController;
use App\Http\Controllers\Admin\Setting\BannerController;
use App\Http\Controllers\Admin\Setting\SocialGroupController;

use App\Http\Controllers\User\Utility\PsychologyCalculatorController as UserPsychologyCalculatorController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\AnnouncementController as UserAnnouncementController;
use App\Http\Controllers\User\SocialGroupController as UserSocialGroupController;
use App\Http\Controllers\User\FaqController as UserFaqController;
use App\Http\Controllers\User\NewsController as UserNewsController;

use App\Http\Controllers\User\TryOut\CategoryController as UserCategoryController;
use App\Http\Controllers\User\TryOut\LessonCategoryController as UserLessonCategoryController;
use App\Http\Controllers\User\TryOut\LessonController as UserLessonController;
use App\Http\Controllers\User\TryOut\ExamController as UserExamController;
use App\Http\Controllers\User\TryOut\GradeController as UserGradeController;
use App\Http\Controllers\User\TryOut\ExamGroupController as UserExamGroupController;

use App\Http\Controllers\User\Transaction\VoucherController as UserVoucherController;
use App\Http\Controllers\User\Transaction\TransactionController as UserTransactionController;
use App\Http\Controllers\User\Transaction\VoucherActivationController as UserVoucherActivationController;

use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\AccountBalanceController as UserAccountBalanceController;

use App\Http\Controllers\User\Material\ModuleController as UserModuleController;
use App\Http\Controllers\User\Material\VideoModuleController as UserVideoModuleController;
use App\Http\Controllers\User\Material\CourseController as UserCourseController;
use App\Http\Controllers\User\Affiliate\AffiliateController as UserAffiliteController;
use App\Http\Controllers\User\Affiliate\UserBankController;

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    return 'Config cache cleared';
});

Route::get('change-question-json', [TestingController::class, 'changeQuestionJson']);
Route::get('change-transactions', [TestingController::class, 'changeTransaction']);
Route::get('check-slope', [TestingController::class, 'checkSlope']);
Route::get('testing-wa', [TestingController::class, 'testingWa']);
Route::get('testing', [TestingController::class, 'index']);
Route::get('local-storage', [TestingController::class, 'localStorage']);
Route::get('on-progress', [TestingController::class, 'onprogress']);
Route::get('check-ip', [TestingController::class, 'checkIp']);
Route::get('403', [TestingController::class, 'privateAccess'])->name('private-access');
Route::get('check-config-midtrans', [TestingController::class, 'checkConfigMidtrans']);

Route::get('check-connection', [TestingController::class, 'checkConnection']);
Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/physical-calculations', [HomeController::class,'physicalCalculation'])->name('landing_page.physical-calculations');
Route::post('/physical-calculations', [HomeController::class,'physicalCalculate'])->name('landing_page.physical-calculations.store');

Route::post('upload', [DashboardController::class, 'upload'])->name('upload');

// admin site
Route::group(['middleware' => ['auth', 'admin', 'accountIsActive'], 'prefix' => 'admin', 'as' => 'admin.'], function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/grade-generators', GradeGeneratorController::class)->only(['index', 'store']);
    Route::get('/grade-generators/delete-all', [GradeGeneratorController::class, 'deleteAll']);
    Route::get('/grade-generators/{id}/export-pdf', [GradeGeneratorController::class, 'exportPdf']);

    Route::get('psychology-calculators', [PsychologyCalculatorController::class, 'index'])->name('psychology-calculators.index');
    Route::get('psychology-calculators/categories/{categoryId}/value-category-groups/{valueCategoryGroupId}/calculate-score', [PsychologyCalculatorController::class, 'calculateScore']);

    Route::get('/settings/applications', [SettingController::class, 'application'])->name('setting.application');
    Route::post('/settings/applications', [SettingController::class, 'storeApplication']);

    Route::get('/settings/authentications', [SettingController::class, 'authentication'])->name('setting.authentication');
    Route::post('/settings/authentications', [SettingController::class, 'storeAuthentication']);
    
    Route::get('/settings/transactions', [SettingController::class, 'transaction'])->name('setting.transaction');
    Route::post('/settings/transactions', [SettingController::class, 'storeTransaction']);
    
    Route::get('/settings/notifications', [SettingController::class, 'notification'])->name('setting.notification');
    Route::post('/settings/notifications', [SettingController::class, 'storeNotification']);

    Route::get('/settings/site-configurations', [SettingController::class, 'siteConfiguration'])->name('setting.site-configuration');
    Route::post('/settings/site-configurations', [SettingController::class, 'storeSiteConfiguration']);

    Route::get('/settings/affiliates', [SettingController::class, 'affiliate'])->name('setting.affiliates');
    Route::post('/settings/affiliates', [SettingController::class, 'storeAffiliate']);

    Route::post('/settings/test-send-message', [SettingController::class, 'testSendMessage']);
    Route::resource('/settings', SettingController::class)->only(['index', 'store']);
    Route::delete('/settings/delete-background', [SettingController::class, 'deleteBackground']);

    Route::resource('/categories', CategoryController::class)->except(['show']);
    Route::get('categories/{id}/change-status', [CategoryController::class, 'changeStatus']);
    Route::get('categories/{id}/lesson-categories', [CategoryController::class, 'getLessonCategory'])->name('categories.lesson-categories');
    Route::get('categories/{id}/vouchers', [CategoryController::class, 'getVoucher'])->name('categories.vouchers');
    Route::get('categories/{id}/sub-categories', [CategoryController::class, 'getSubCategory'])->name('categories.sub-categories');
    Route::get('categories/{id}/value-category-groups', [CategoryController::class, 'getValueCategoryGroup'])->name('categories.value-category-groups');
    
    Route::resource('/sub-categories', SubCategoryController::class)->except(['show']);
    Route::resource('announcements', AnnouncementController::class);

    Route::resource('faqs', FaqController::class);
    Route::resource('news', NewsController::class);
    Route::get('news/{id}/change-status', [NewsController::class, 'changeStatus']);
    Route::resource('lesson-categories', LessonCategoryController::class)->except(['show']);
    Route::get('lesson-categories/{id}/change-status', [LessonCategoryController::class, 'changeStatus']);
    Route::get('lesson-categories/{id}/exam-groups', [LessonCategoryController::class, 'getExamGroup'])->name('lesson-categories.exam-groups');

    Route::get('lesson-categories/{id}/lessons', [LessonCategoryController::class, 'getLesson']);
    Route::resource('lessons', LessonController::class);
    Route::get('lessons/{id}/change-status', [LessonController::class, 'changeStatus']);
    Route::get('lessons/{id}/question-titles', [LessonController::class, 'getQuestionTitle'])->name('lessons.question-titles');
    Route::get('lessons/{id}/exams', [LessonController::class, 'getExam'])->name('lessons.exams');

    Route::resource('value-category-groups', ValueCategoryGroupController::class)->except(['show']);
    Route::resource('value-category-groups.value-categories', ValueCategoryController::class)->except(['show']);
    Route::resource('value-category-groups.value-categories.detail-value-categories', DetailValueCategoryController::class)->except(['show']);

    Route::resource('question-titles', QuestionTitleController::class)->except(['show']);
    Route::get('question-titles/{id}/format-import-excel', [QuestionTitleController::class, 'importExcelFormat'])->name('question-titles.format-import-excel');

    Route::resource('question-titles.questions', QuestionController::class)->except(['show']);
    Route::get('question-titles/{id}/questions/generate-questions', [QuestionController::class, 'generateQuestionCreate'])->name('question-titles.questions.generate_question_templates');
    Route::post('question-titles/{id}/questions/generate-questions', [QuestionController::class, 'generateQuestionStore']);
    Route::resource('question-titles.question-templates', QuestionTemplateController::class);
    Route::get('question-titles/{id}/delete-question', [QuestionController::class, 'deleteQuestion'])->name('question-titles.questions.delete-question');
    Route::post('question-titles/{id}/import-excel', [QuestionController::class, 'importExcel'])->name('question-titles.questions.import-excel');
    Route::get('question-titles/{id}/export-excel', [QuestionController::class, 'exportExcel'])->name('question-titles.questions.export-excel');
    Route::get('question-titles/{id}/export-pdf', [QuestionController::class, 'exportPdf'])->name('question-titles.questions.export-pdf');
    Route::post('question-titles/{id}/questions/generate-question', [QuestionController::class, 'generateQuestion'])->name('question_titles.questions.generate_question');

    Route::get('users/import', [UserController::class, 'showImportForm'])->name('users.import_form');
    Route::post('users/import', [UserController::class, 'import']);
    Route::get('users/export', [UserController::class, 'export']);
    Route::resource('users', UserController::class);
    Route::get('users/{id}/change-user-status', [UserController::class, 'changeStatus']);
    Route::get('users/{user}/send-activation-link', [UserController::class, 'sendActivationLink'])->name('users.send_activation_link');
    Route::get('users/{user}/activation-reminder', [UserController::class, 'activationReminder'])->name('users.activation_reminder');
    Route::delete('users/{user}/user-member-categories/{memberCategoryId}', [UserController::class, 'destroyMemberCategoryUser'])->name('users.destroy_member_category');
    Route::delete('users/{user}/sessions/{sessionId}', [UserController::class, 'destroySession'])->name('users.destroy_session');
    Route::delete('users/{user}/user-access/{userAccessId}', [UserController::class, 'destroyUserAccess'])->name('users.destroy_user_access');

    Route::resource('exams', ExamController::class);
    Route::get('exams/{id}/change-exam-status', [ExamController::class, 'changeExamStatus']);
    Route::get('exams/{id}/export-pdf', [ExamController::class, 'exportPdf']);
    Route::get('exams/{id}/export-excel', [ExamController::class, 'exportExcel']);
    Route::delete('exams/{id}/delete-exam-result', [ExamController::class, 'deleteExamResult']);
    Route::get('exams/grades/{gradeid}', [ExamController::class, 'deleteExam']);
    Route::get('exams/{id}/grades/{gradeId}', [ExamController::class, 'userGrade']);
    Route::get('exams/{id}/grades/{gradeId}/answer-corrections', [ExamController::class, 'answerCorrection']);
    Route::get('exams/grades/{gradeid}/regenerate-scores', [ExamController::class, 'regenerateScore']);
    Route::get('exams/{id}/regenerate-block-tokens', [ExamController::class, 'regenerateBlockToken']);
    Route::delete('exams/grades/{id}/destroy', [ExamController::class, 'deleteExam']);
    
    Route::resource('exam-groups', ExamGroupController::class);
    Route::get('exam-groups/{id}/change-exam-status', [ExamGroupController::class, 'changeExamStatus']);
    Route::get('exam-groups/{id}/regenerate-block-tokens', [ExamGroupController::class, 'regenerateBlockToken']);
    Route::delete('exam-groups/{id}/delete-exam-group-result', [ExamGroupController::class, 'deleteExamGroupResult']);
    Route::resource('exam-groups.exam-group-details', ExamGroupDetailController::class);
    Route::get('exam-groups/{id}/exam-group-details/{exam_id}/export-pdf', [ExamGroupDetailController::class, 'exportPdf']);
    Route::get('exam-groups/{id}/exam-group-details/{exam_id}/export-excel', [ExamGroupDetailController::class, 'exportExcel']);
    Route::get('exam-groups/{id}/exam-group-details/{exam_id}/grades/{grade_id}', [ExamGroupDetailController::class, 'userGrade']);
    Route::get('exam-groups/{id}/students', [ExamGroupController::class, 'examGroupStudent']);
    Route::get('exam-groups/{id}/export-pdf', [ExamGroupController::class, 'examGroupExportPdf']);
    Route::get('exam-groups/{id}/export-excel', [ExamGroupController::class, 'examGroupExportExcel']);
    Route::get('exam-groups/{id}/students/{exam_group_user_id}/grades', [ExamGroupController::class, 'examGroupStudentGrade']);
    Route::get('exam-groups/{id}/students/{exam_group_user_id}/regenerate-scores', [ExamGroupController::class, 'regenerateScore']);
    Route::get('exam-groups/{id}/students/regenerate-all-scores', [ExamGroupController::class, 'regenerateAllScore']);
    Route::get('exam-groups/{id}/students/{exam_group_user_id}/export-pdf', [ExamGroupController::class, 'examGroupStudentExportPdf']);
    Route::delete('exam-groups/{id}/exam-group-users', [ExamGroupController::class, 'examGroupUserDestroy']);

    Route::resource('banks', BankController::class)->except(['show']);
    Route::resource('payment-methods', PaymentMethodController::class)->only(['index', 'edit', 'update']);
    Route::resource('vouchers', VoucherController::class)->except(['show']);
    Route::get('vouchers/{id}/change-voucher-status', [VoucherController::class, 'changeStatus']);
    Route::resource('member-categories', MemberCategoryController::class)->except(['show']);
    Route::resource('voucher-codes', VoucherCodeController::class)->except(['show']);
    Route::resource('modules', ModuleController::class)->except(['show']);
    Route::get('modules/{id}/change-module-status', [ModuleController::class, 'changeStatus']);
    Route::resource('modules.detail-modules', DetailModuleController::class);
    Route::resource('video-modules', VideoModuleController::class)->except(['show']);
    Route::get('video-modules/{id}/change-video-module-status', [VideoModuleController::class, 'changeStatus']);
    Route::resource('video-modules.detail-video-modules', DetailVideoModuleController::class);
    Route::resource('courses', CourseController::class);
    Route::get('courses/{id}/change-course-status', [CourseController::class, 'changeStatus']);
    Route::resource('courses.course-details', CourseDetailController::class);
    Route::resource('courses.course-sections', CourseSectionController::class);

    Route::resource('internet-protocol-whitelists', InternetProtocolWhitelistController::class)->except(['show']);
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::resource('social-groups', SocialGroupController::class)->except(['show']);
    Route::get('banners/{id}/change-status', [BannerController::class, 'changeStatus']);

    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');

    Route::resource('/affiliates/commissions', CommissionController::class)->only(['index', 'show']);
    Route::resource('/affiliates/withdrawals', WithdrawalController::class)->except(['destroy']);
});

Route::group(['middleware' => ['ipWhitelist']], function() {
    // user site
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['auth', 'accountIsActive', 'auth.session', 'ipWhitelist'], 'prefix' => 'user', 'as' => 'user.'], function() {
        // dashboard
        Route::get('dashboard', UserDashboardController::class)->name('user.dashboard');

        Route::get('account-balances/reedem-voucher', [UserAccountBalanceController::class, 'reedemVoucher']);

        Route::get('psychology-calculators', [UserPsychologyCalculatorController::class, 'index'])->name('psychology-calculators.index');
        Route::get('psychology-calculators/categories/{categoryId}/value-category-groups/{valueCategoryGroupId}/calculate-score', [UserPsychologyCalculatorController::class, 'calculateScore']);

        Route::resource('account-balances', UserAccountBalanceController::class)->only(['index', 'store']);
        Route::resource('announcements', UserAnnouncementController::class)->only(['index', 'show']);
        Route::resource('faqs', UserFaqController::class)->only(['index']);
        Route::resource('social-groups', UserSocialGroupController::class)->only(['index']);
        Route::resource('news', UserNewsController::class)->only(['index', 'show']);
        Route::resource('categories', UserCategoryController::class)->only(['index']);
        Route::resource('categories.lesson-categories', UserLessonCategoryController::class)->only(['index']);
        Route::resource('categories.lesson-categories.lessons', UserLessonController::class)->only(['index']);
        
        Route::resource('categories.lesson-categories.lessons.exams', UserExamController::class)->only(['index', 'show']);
        Route::get('exams/{id}/exam-start', [UserExamController::class, 'examStart'])->name('exams.exam-start');
        Route::get('exams/{id}/exam-reset', [UserExamController::class, 'examReset'])->name('exams.exam-reset');
        Route::get('exams/{exam_id}/grades/{grade_id}/sections/{section}', [UserExamController::class, 'examShowQuestion'])->name('exams.exam-show-questions');
        Route::post('exams/{id}/exam-end', [UserExamController::class, 'endExam'])->name('exams.exam-end');
        Route::post('exams/{id}/decrement-tolerance', [UserExamController::class, 'decrementTolerance'])->name('exams.decrement-tolerance');
        Route::get('exams/{exam_id}/grades/{grade_id}/unblocked', [UserExamController::class, 'unblocked']);
        Route::get('exams/{exam_id}/grades/{grade_id}/finished', [UserExamController::class, 'finished']);

        Route::resource('grades', UserGradeController::class)->only(['index', 'show']);
        Route::get('grades/{grade_id}/questions', [UserGradeController::class, 'question'])->name('grades.questions');

        Route::get('exam-groups/{id}/exam-start', [UserExamGroupController::class, 'examStart'])->name('exam-groups.exam-start');
        Route::get('exam-groups/{id}/exam-reset', [UserExamController::class, 'examReset'])->name('exam-groups.exam-reset');
        Route::get('exam-groups/{id}/sections/{section}', [UserExamGroupController::class, 'examShowQuestion'])->name('exam-groups.exam-show-questions');
        Route::post('exam-groups/{id}/exam-end', [UserExamGroupController::class, 'endExam'])->name('exam-groups.exam-end');
        Route::post('exam-groups/{id}/decrement-tolerance', [UserExamGroupController::class, 'decrementTolerance'])->name('exam-groups.decrement-tolerance');
        Route::get('exam-groups/{exam_group_id}/grades/{exam_group_user_id}/unblocked', [UserExamGroupController::class, 'unblocked']);
        Route::get('exam-groups/{exam_group_id}/grades/{exam_group_user_id}/finished', [UserExamGroupController::class, 'finished']);

        Route::get('exam-groups', [UserExamGroupController::class, 'index']);
        Route::get('exam-groups/histories', [UserExamGroupController::class, 'examGroupHistory']);
        Route::get('exam-groups/histories/{id}', [UserExamGroupController::class, 'examGroupHistoryDetail'])->name('exam-groups.histories.detail');
        Route::get('exam-groups/{id}/export-pdf', [UserExamGroupController::class, 'examGroupStudentExportPdf']);
        Route::get('exam-groups/{id}/lesson-categories', [UserExamGroupController::class, 'examGroupDetail']);
        Route::get('exam-groups/{id}/lesson-categories/{lessonCategoryId}/exams', [UserExamGroupController::class, 'examGroupList']);
        Route::get('exam-groups/{id}/lesson-categories/{lessonCategoryId}/exams/{examGroupId}', [UserExamGroupController::class, 'examGroupShow'])->name('exam_group.show');

        Route::resource('vouchers', UserVoucherController::class)->only(['index']);

        Route::get('transactions/success', [UserTransactionController::class, 'transactionSuccess']);
        Route::resource('transactions', UserTransactionController::class)->only(['index', 'show']);
        Route::get('transactions/{transaction}/payment-confirmations', [UserTransactionController::class, 'paymentConfirmation'])->name('transactions.payment-confirmations');
        Route::post('transactions/{transaction}/payment-confirmations', [UserTransactionController::class, 'storePaymentConfirmation']);
        Route::get('transactions/{type}/{id}/buy', [UserTransactionController::class, 'buy'])->name('transactions.buy');
        Route::post('transactions/{type}/{id}/buy', [UserTransactionController::class, 'buyStore'])->name('transactions.buy.store');

        Route::resource('modules', UserModuleController::class)->only(['index', 'show']);
        Route::get('modules/{id}/detail-modules/{detail_module_id}', [UserModuleController::class, 'detailModule']);

        Route::resource('video-modules', UserVideoModuleController::class)->only(['index', 'show']);
        Route::get('video-modules/{id}/detail-video-modules/{detail_video_module_id}', [UserVideoModuleController::class, 'detailVideoModule']);

        Route::resource('courses', UserCourseController::class)->only(['index', 'show']);

        Route::resource('voucher-activations', UserVoucherActivationController::class)->only(['index', 'store']);

        Route::resource('users', UserUserController::class)->only(['index', 'edit', 'update']);

        Route::get('affiliates/balances', [UserAffiliteController::class, 'index']);
        Route::get('affiliates/referrals', [UserAffiliteController::class, 'referrals']);
        Route::get('affiliates/term-conditions', [UserAffiliteController::class, 'termCondition']);
        Route::get('affiliates/links', [UserAffiliteController::class, 'link'])->name('affiliates.links');
        Route::get('affiliates/links/{id}/edit', [UserAffiliteController::class, 'editLink']);
        Route::put('affiliates/links/{id}', [UserAffiliteController::class, 'updateLink']);
        Route::get('affiliates/withdrawals', [UserAffiliteController::class, 'withdrawal'])->name('affiliates.withdrawals');
        Route::get('affiliates/withdrawals/create', [UserAffiliteController::class, 'createWithdrawal']);
        Route::post('affiliates/withdrawals', [UserAffiliteController::class, 'storeWithdrawal']);
        Route::get('affiliates/withdrawals/{id}', [UserAffiliteController::class, 'showWithdrawal']);

        Route::resource('affiliates/user-banks', UserBankController::class)->except(['show']);
    });
});

Route::get('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

// regional
Route::get('region/province', [RegionController::class, 'province']);
Route::get('region/city/{provinsi_id}', [RegionController::class, 'city']);
Route::get('region/district/{city_id}', [RegionController::class, 'district']);
Route::get('region/village/{district_id}', [RegionController::class, 'village']);

Route::get('user/{id}/activation', [ActivationController::class, 'activation']);
Route::get('user/{id}/activation/actived', [ActivationController::class, 'actived']);
Route::get('user/forgot-password', [ActivationController::class, 'forgotPassword']);
Route::post('user/forgot-password', [ActivationController::class, 'storeForgotPassword']);
Route::get('user/reset-password', [ActivationController::class, 'resetPassword'])->name('password.reset');
Route::post('user/reset-password', [ActivationController::class, 'storeResetPassword'])->name('password.reset.store');