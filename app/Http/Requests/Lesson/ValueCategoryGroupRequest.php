<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class ValueCategoryGroupRequest extends FormRequest
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
            'category_id' => 'required',
            'name' => 'required|string|max:191',
            'show_calculator' => 'required|in:0,1',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('value_category_group.category_id'),
            'name' => __('value_category_group.name'),
            'show_calculator' => __('value_category_group.show_calculator'),
        ];
    }
}
