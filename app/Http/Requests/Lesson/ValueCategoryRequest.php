<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class ValueCategoryRequest extends FormRequest
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
            'value_category_group_id' => 'required',
            'lesson_id' => 'required',
            'name' => 'required|string|max:191',
            'assessment_type' => 'required',
            'add_relative_score' => 'required',
            'relative_score_formulation' => 'required_if:add_relative_score,1',
            'add_weighted_score' => 'required',
            'weighted_score_formulation' => 'required_if:add_weighted_score,1',
            'multiplier' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'value_category_group_id' => __('value_category.value_category_group_id'),
            'lesson_id' => __('value_category.lesson_id'),
            'name' => __('value_category.name'),
            'assessment_type' => __('value_category.assessment_type'),
            'add_relative_score' => __('value_category.add_relative_score'),
            'relative_score_formulation' => __('value_category.relative_score_formulation'),
            'add_weighted_score' => __('value_category.add_weighted_score'),
            'weighted_score_formulation' => __('value_category.weighted_score_formulation'),
            'multiplier' => __('value_category.multiplier'),
        ];
    }
}
