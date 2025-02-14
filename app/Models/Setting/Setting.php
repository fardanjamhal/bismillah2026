<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_methods' => 'array',
        'paid_member_access' => 'array',
        'free_member_access' => 'array',
        'authentication_field' => 'array',
        'login_type' => 'array',

        'practice_question_statuses' => 'array',
        'tryout_statuses' => 'array',
        'module_material_statuses' => 'array',
        'video_module_statuses' => 'array',
        'course_statuses' => 'array',
    ];

    protected $fillable = [
        'id',
        // setting applications
        'app_name',
        'app_url',
        'logo',
        'favicon',
        'signed_name',
        'signed_image',
        'authentication_background',
        'address',
        'timezone',
        'block_system_type',    
        'public_access',
        'social_group_mode',

        // setting authentication
        'add_activation_user',
        'add_user_registration',
        'category_access',
        'registration_member_type',
        'login_type', // 1 email, 2 username, 3 email / username
        'device_login_limit',
        'authentication_field',
        'paid_member_access',
        'free_member_access',    
        'activation_page_background',
        'forgot_password_page_background',
        'login_page_background',
        'register_page_background',

        // setting transactions
        'enable_practice_question_sales',
        'practice_question_sales_type',
        'enable_tryout_sales',
        'tryout_sales_type',
        'enable_module_material_sales',
        'module_material_sales_type',
        'enable_video_module_sales',
        'video_module_sales_type',
        'enable_course_sales',
        'course_sales_type',
        'payment_methods',
        'payment_gateway_mode',
        'add_transaction_notification',
        'minimum_topup_balance',

        // setting statuses
        'practice_question_statuses',
        'tryout_statuses',
        'module_material_statuses',
        'video_module_statuses',
        'course_statuses',

        // setting notification
        'notification_type', // 1 whatsapp, 2 email
        'whatsapp_number',
        'whatsapp_token',

        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',

        'account_activation_notification_is_active',
        'account_activation_notification',
        'account_activation_reminder_notification_is_active',
        'account_activation_reminder_notification',
        'transaction_pending_notification_is_active',
        'transaction_pending_notification',
        'transaction_paid_notification_is_active',
        'transaction_paid_notification',
        'transaction_failed_notification_is_active',
        'transaction_failed_notification',
        'transaction_done_notification_is_active',
        'transaction_done_notification',
        'transaction_expired_notification_is_active',
        'transaction_expired_notification',

        // setting meta tags
        'page_name', // Nama unik halaman (misalnya: home, about)
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'meta_url',
        'meta_favicon',
        'og_type',
        'twitter_card',

        // setting referral
        'enable_affiliate_feature',
        'show_affiliator',
        'terms_and_condition',
        'commission_type', // 1 percentage, 2 nominal
        'commission',
        'minimum_withdrawal',
        'admin_fee',

        // setting etc
        'theme',
        'add_data_detail_for_registration',
        'sidebar_color',
        'header_color',
    ];
}
