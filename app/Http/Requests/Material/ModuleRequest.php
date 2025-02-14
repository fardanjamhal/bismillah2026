<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'category_id' => 'required',
            'title' => 'required|string|max:191',
            'thumbnail' => 'required',
            'description' => 'required',
            'order' => 'required|numeric',
            'price_type' => 'required',
            'price_before_discount' => 'required_if:price_type,2',
            'price_after_discount' => 'required_if:price_type,2',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('module.category_id'),
            'title' => __('module.title'),
            'thumbnail' => __('module.thumbnail'),
            'description' => __('module.description'),
            'order' => __('module.order'),
            'price_type' => __('module.price_type'),
            'price_before_discount' => __('module.price_before_discount'),
            'price_after_discount' => __('module.price_after_discount'),
            'status' => __('module.status'),
        ];
    }
}
