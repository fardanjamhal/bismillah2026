<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
            'add_activation_user' => 'required|in:0,1', 
            'add_user_registration' => 'required|in:0,1', 
            'category_access' => 'required|in:1,2',
            'registration_member_type' => 'required|in:1,2', 
            'login_type' => 'required', 
            'device_login_limit' => 'sometimes', 
            'paid_member_access' => 'required', 
            'free_member_access' => 'required', 
            'activation_page_background' => 'required', 
            'forgot_password_page_background' => 'required', 
            'login_page_background' => 'required', 
            'register_page_background' => 'required', 
        ];
    }

    public function attributes()
    {
        return [
            'add_activation_user' => __('setting.add_activation_user'),
            'add_user_registration' => __('setting.add_user_registration'),
            'category_access' => __('setting.category_access'),
            'registration_member_type' => __('setting.registration_member_type'),
            'login_type' => __('setting.login_type'),
            'device_login_limit' => __('setting.device_login_limit'),
            'paid_member_access' => __('setting.app_name'),
            'free_member_access' => __('setting.free_member_access'),
            'activation_page_background' => __('setting.activation_page_background'),
            'forgot_password_page_background' => __('setting.forgot_password_page_background'),
            'login_page_background' => __('setting.login_page_background'),
            'register_page_background' => __('setting.register_page_background'),
        ];
    }
}
