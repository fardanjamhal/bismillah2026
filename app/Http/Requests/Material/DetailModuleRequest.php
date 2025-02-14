<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class DetailModuleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'required|string|max:191',
            'link' => 'required|string|max:191',
            'description' => 'required',
            'order' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('detail_module.title'),
            'link' => __('detail_module.link'),
            'description' => __('detail_module.description'),
            'order' => __('detail_module.order'),
        ];
    }
}
