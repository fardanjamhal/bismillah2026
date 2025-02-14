<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseDetailRequest extends FormRequest
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
            'course_section_id' => 'required',
            'title' => 'required|string|max:191',
            'link' => 'sometimes',
            'description' => 'required',
            'order' => 'required|numeric',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function attributes()
    {
        return [
            'course_section_id' => __('course_detail.course_section_id'),
            'title' => __('course_detail.title'),
            'link' => __('course_detail.link'),
            'description' => __('course_detail.description'),
            'order' => __('course_detail.order'),
            'is_active' => __('course_detail.is_active'),
        ];
    }
}
