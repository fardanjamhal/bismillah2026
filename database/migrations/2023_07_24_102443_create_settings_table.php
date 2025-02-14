<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // setting applications
            $table->string('app_name');
            $table->string('app_url');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('signed_name');
            $table->string('signed_image');
            $table->string('authentication_background')->nullable();
            $table->text('address');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->tinyInteger('block_system_type')->default(1);    
            $table->tinyInteger('public_access')->default(1);
            $table->tinyInteger('social_group_mode')->default(0);

            // setting authentication
            $table->tinyInteger('add_activation_user')->default(0);
            $table->integer('add_user_registration')->default(1);
            $table->integer('category_access')->default(1);
            $table->tinyInteger('registration_member_type')->default(2);
            $table->json('login_type')->nullable(); // 1 email, 2 username, 3 email / username
            $table->integer('device_login_limit')->nullable();
            $table->json('authentication_field')->nullable();
            $table->json('paid_member_access')->nullable();
            $table->json('free_member_access')->nullable();    
            $table->string('activation_page_background');
            $table->string('forgot_password_page_background');
            $table->string('login_page_background');
            $table->string('register_page_background');

            // setting transactions
            $table->tinyInteger('enable_practice_question_sales')->default(1);
            $table->tinyInteger('practice_question_sales_type')->default(1);
            $table->tinyInteger('enable_tryout_sales')->default(1);
            $table->tinyInteger('tryout_sales_type')->default(1);
            $table->tinyInteger('enable_module_material_sales')->default(1);
            $table->tinyInteger('module_material_sales_type')->default(1);
            $table->tinyInteger('enable_video_module_sales')->default(1);
            $table->tinyInteger('video_module_sales_type')->default(1);
            $table->tinyInteger('enable_course_sales')->default(1);
            $table->tinyInteger('course_sales_type')->default(1);
            $table->json('payment_methods')->nullable();
            $table->tinyInteger('payment_gateway_mode')->default(0);
            $table->tinyInteger('add_transaction_notification')->default(0);
            $table->double('minimum_topup_balance')->nullable();

            // setting statuses
            $table->json('practice_question_statuses')->nullable();
            $table->json('tryout_statuses')->nullable();
            $table->json('module_material_statuses')->nullable();
            $table->json('video_module_statuses')->nullable();
            $table->json('course_statuses')->nullable();

            // setting notification
            $table->tinyInteger('notification_type')->default(1); // 1 whatsapp, 2 email
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_token')->nullable();
            $table->string('mail_mailer')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();
            $table->tinyInteger('account_activation_notification_is_active')->default(1);
            $table->longtext('account_activation_notification')->nullable();
            $table->tinyInteger('account_activation_reminder_notification_is_active')->default(1);
            $table->longtext('account_activation_reminder_notification')->nullable();
            $table->tinyInteger('transaction_pending_notification_is_active')->default(1);
            $table->longtext('transaction_pending_notification')->nullable();
            $table->tinyInteger('transaction_paid_notification_is_active')->default(1);
            $table->longtext('transaction_paid_notification')->nullable();
            $table->tinyInteger('transaction_failed_notification_is_active')->default(1);
            $table->longtext('transaction_failed_notification')->nullable();
            $table->tinyInteger('transaction_done_notification_is_active')->default(1);
            $table->longtext('transaction_done_notification')->nullable();
            $table->tinyInteger('transaction_expired_notification_is_active')->default(1);
            $table->longtext('transaction_expired_notification')->nullable();

            // setting meta tags
            $table->string('page_name')->nullable(); // Nama unik halaman (misalnya: home, about)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('meta_url')->nullable();
            $table->string('meta_favicon')->nullable();
            $table->string('og_type')->default('website');
            $table->string('twitter_card')->default('summary_large_image');

            // setting referral
            $table->tinyInteger('enable_affiliate_feature')->default(0);
            $table->tinyInteger('show_affiliator')->default(0);
            $table->text('terms_and_condition')->nulable();
            $table->tinyInteger('commission_type')->default(1); // 1 percentage, 2 nominal
            $table->double('commission');
            $table->double('minimum_withdrawal');
            $table->double('admin_fee');

            // setting etc
            $table->integer('theme')->default(1);
            $table->integer('add_data_detail_for_registration')->default(1);
            $table->string('sidebar_color')->nullable();
            $table->string('header_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
