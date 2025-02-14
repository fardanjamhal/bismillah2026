<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SocialGroupRequest extends FormRequest
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
            'logo' => request()->isMethod('PUT') ? 'sometimes' : 'required',
            'link' => 'required',
            'description' => 'required',
            'order' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('social_group.name'),
            'logo' => __('social_group.logo'),
            'link' => __('social_group.link'),
            'description' => __('social_group.description'),
            'order' => __('social_group.order'),
        ];
    }
}
