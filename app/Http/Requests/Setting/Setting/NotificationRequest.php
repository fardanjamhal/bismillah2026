<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notification_type' => 'required|in:1,2',
            'whatsapp_number' => 'required_if:notification_type,1|max:191',
            'whatsapp_token' => 'required_if:notification_type,1|max:191', 
            'mail_mailer' => 'required_if:notification_type,2|max:191',
            'mail_host' => 'required_if:notification_type,2|max:191',
            'mail_port' => 'required_if:notification_type,2|max:191',
            'mail_username' => 'required_if:notification_type,2|max:191',
            'mail_password' => 'required_if:notification_type,2|max:191',
            'mail_encryption' => 'required_if:notification_type,2|max:191',
            'mail_from_address' => 'required_if:notification_type,2|max:191',
            'mail_from_name' => 'required_if:notification_type,2|max:191',

            'account_activation_notification_is_active' => 'required', 
            'account_activation_notification' => 'required_if:account_activation_notification_is_active,1', 

            'account_activation_reminder_notification_is_active' => 'required', 
            'account_activation_reminder_notification' => 'required_if:account_activation_reminder_notification_is_active,1', 

            'transaction_pending_notification_is_active' => 'required', 
            'transaction_pending_notification' => 'required_if:transaction_pending_notification_is_active,1', 

            'transaction_paid_notification_is_active' => 'required', 
            'transaction_paid_notification' => 'required_if:transaction_paid_notification_is_active,1', 

            'transaction_done_notification_is_active' => 'required', 
            'transaction_done_notification' => 'required_if:transaction_done_notification_is_active,1', 

            'transaction_expired_notification_is_active' => 'required', 
            'transaction_expired_notification' => 'required_if:transaction_expired_notification_is_active,1', 

            'transaction_failed_notification_is_active' => 'required', 
            'transaction_failed_notification' => 'required_if:transaction_failed_notification_is_active,1', 
        ];
    }

    public function attributes()
    {
        return [
            'notification_type' => __('setting.notification_type'),
            'whatsapp_number' => __('setting.whatsapp_number'),
            'whatsapp_token' => __('setting.whatsapp_token'),
            'mail_mailer' => __('setting.mail_mailer'),
            'mail_host' => __('setting.mail_host'),
            'mail_port' => __('setting.mail_port'),
            'mail_username' => __('setting.mail_username'),
            'mail_password' => __('setting.mail_password'),
            'mail_encryption' => __('setting.mail_encryption'),
            'mail_from_address' => __('setting.mail_from_address'),
            'mail_from_name' => __('setting.mail_from_name'),
            'account_activation_notification_is_active' => __('setting.account_activation_notification_is_active'),
            'account_activation_notification' => __('setting.account_activation_notification'),
            'account_activation_reminder_notification_is_active' => __('setting.account_activation_reminder_notification_is_active'),
            'account_activation_reminder_notification' => __('setting.account_activation_reminder_notification'),
            'transaction_pending_notification_is_active' => __('setting.transaction_pending_notification_is_active'),
            'transaction_pending_notification' => __('setting.transaction_pending_notification'),
            'transaction_paid_notification_is_active' => __('setting.transaction_paid_notification_is_active'),
            'transaction_paid_notification' => __('setting.transaction_paid_notification'),
            'transaction_done_notification_is_active' => __('setting.transaction_done_notification_is_active'),
            'transaction_done_notification' => __('setting.transaction_done_notification'),
            'transaction_expired_notification_is_active' => __('setting.transaction_expired_notification_is_active'),
            'transaction_expired_notification' => __('setting.transaction_expired_notification'),
            'transaction_failed_notification_is_active' => __('setting.transaction_failed_notification_is_active'),
            'transaction_failed_notification' => __('setting.transaction_failed_notification'),
        ];
    }
}
