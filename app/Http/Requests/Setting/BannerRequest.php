<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'image' => request()->isMethod('PUT') ? 'sometimes' : 'required',
            'order' => 'required',
            'is_active' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('banner.name'),
            'image' => __('banner.image'),
            'order' => __('banner.order'),
            'is_active' => __('banner.is_active'),
        ];
    }
}
