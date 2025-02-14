<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'video_intro' => 'required',
            'prerequisite' => 'required',
            'sort_description' => 'required',
            'description' => 'required',
            'order' => 'required|numeric',
            'price_type' => 'required',
            'price_before_discount' => 'required_if:price_type,2',
            'price_after_discount' => 'required_if:price_type,2',
            'goals.*' => 'required|max:191',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('course.category_id'),
            'title' => __('course.title'),
            'thumbnail' => __('course.thumbnail'),
            'video_intro' => __('course.video_intro'),
            'prerequisite' => __('course.prerequisite'),
            'sort_description' => __('course.sort_description'),
            'description' => __('course.description'),
            'order' => __('course.order'),
            'price_type' => __('course.price_type'),
            'price_before_discount' => __('course.price_before_discount'),
            'price_after_discount' => __('course.price_after_discount'),
            'goals.*' => __('course.goals'),
            'status' => __('course.status'),
        ];
    }
}
