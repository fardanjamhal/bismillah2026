<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SiteConfigurationRequest extends FormRequest
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
            'page_name' => 'required|string|max:191',
            'meta_title' => 'required|string|max:191',
            'meta_description' => 'required',
            'meta_keywords' => 'required',
            'meta_url' => 'required|string|max:191',
            'meta_image' => 'required',
            'meta_favicon' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'page_name' => __('setting.page_name'),
            'meta_title' => __('setting.meta_title'),
            'meta_description' => __('setting.meta_description'),
            'meta_keywords' => __('setting.meta_keywords'),
            'meta_url' => __('setting.meta_url'),
            'meta_image' => __('setting.meta_image'),
            'meta_favicon' => __('setting.meta_favicon'),
        ];
    }
}
