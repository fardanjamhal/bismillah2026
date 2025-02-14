<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'app_name' => 'required|string|max:191',
            'app_url' => 'required|string|max:191',
            'logo' => 'required',
            'favicon' => 'sometimes',
            'signed_name' => 'required',
            'signed_image' => 'required',
            'authentication_background' => 'sometimes',
            'address' => 'required',
            'timezone' => 'required',
            'block_system_type' => 'required',
            'public_access' => 'required',
            'social_group_mode' => 'required',
            'practice_question_statuses' => 'required',
            'tryout_statuses' => 'required',
            'module_material_statuses' => 'required',
            'video_module_statuses' => 'required',
            'course_statuses' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'app_name' => __('setting.app_name'),
            'app_url' => __('setting.app_url'),
            'logo' => __('setting.logo'),
            'favicon' => __('setting.favicon'),
            'signed_name' => __('setting.signed_name'),
            'signed_image' => __('setting.signed_image'),
            'authentication_background' => __('setting.authentication_background'),
            'block_system_type' => __('setting.block_system_type'),
            'public_access' => __('setting.public_access'),
            'social_group_mode' => __('setting.social_group_mode'),
            'practice_question_statuses' => __('setting.practice_question_statuses'),
            'tryout_statuses' => __('setting.tryout_statuses'),
            'module_material_statuses' => __('setting.module_material_statuses'),
            'video_module_statuses' => __('setting.video_module_statuses'),
            'course_statuses' => __('setting.course_statuses'),
        ];
    }
}
