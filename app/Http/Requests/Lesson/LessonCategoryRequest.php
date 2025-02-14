<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class LessonCategoryRequest extends FormRequest
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
            'description' => 'sometimes',
            'thumbnail' => request()->isMethod('PUT') ? 'sometimes' : 'required',
            'order' => 'required',
            'development_status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('lesson_category.category_id'),
            'name' => __('lesson_category.name'),
            'description' => __('lesson_category.description'),
            'thumbnail' => __('lesson_category.thumbnail'),
            'order' => __('lesson_category.order'),
            'development_status' => __('lesson_category.development_status'),
        ];
    }
}
