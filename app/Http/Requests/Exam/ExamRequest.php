<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
            'lesson_category_id' => 'required',
            'lesson_id' => 'required',
            'question_title_id' => 'required',
            'question_selection_mode' => 'required',
            'number_of_questions' => 'required_if:question_selection_mode,2',
            'title' => 'required',
            'duration' => 'required',
            'random_question' => 'required',
            'random_answer' => 'required',
            'show_answer' => 'required',
            'show_question_number_navigation' => 'required',
            'show_question_number' => 'required',
            'next_question_automatically' => 'required',
            'show_prev_next_button' => 'required',
            'repeat_the_exam' => 'required',
            'show_ranking_exam' => 'required',
            'exam_status' => 'required',
            'show_answer_discussion' => 'required',
            'type_option' => 'required',
            'button_type_finish' => 'required',
            'total_tolerance' => 'sometimes',
            'price_type' => 'required',
            'price_before_discount' => [
            'required_if:price_type,2',
                function ($attribute, $value, $fail) {
                    if ($this->input('price_type') == 2 && $value < 1) {
                        $fail(__('exam_group.price_before_discount') . ' tidak boleh kurang dari 1');
                    }
                },
            ],
            'price_after_discount' => [
                'required_if:price_type,2',

                function ($attribute, $value, $fail) {
                    if ($this->input('price_type') == 2 && $value < 1) {
                        $fail(__('exam_group.price_after_discount') . ' tidak boleh kurang dari 1');
                    }
                    if ($this->input('price_type') == 2) {
                        $priceBeforeDiscount = $this->input('price_before_discount');
                        if ($value > $priceBeforeDiscount) {
                            $fail(__('exam_group.price_after_discount') . ' tidak boleh lebih besar dari harga sebelum diskon.');
                        }
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('exam.category_id'),
            'lesson_category_id' => __('exam.lesson_category_id'),
            'lesson_id' => __('exam.lesson_id'),
            'question_title_id' => __('exam.question_title_id'),
            'question_selection_mode' => __('exam.question_selection_mode'),
            'number_of_questions' => __('exam.number_of_questions'),
            'title' => __('exam.title'),
            'duration' => __('exam.duration'),
            'description' => __('exam.description'),
            'random_question' => __('exam.random_question'),
            'random_answer' => __('exam.random_answer'),
            'show_answer' => __('exam.show_answer'),
            'show_question_number_navigation' => __('exam.show_question_number_navigation'),
            'show_question_number' => __('exam.show_question_number'),
            'next_question_automatically' => __('exam.next_question_automatically'),
            'show_prev_next_button' => __('exam.show_prev_next_button'),
            'type_option' => __('exam.type_option'),
            'repeat_the_exam' => __('exam.repeat_the_exam'),
            'show_ranking_exam' => __('exam.show_ranking_exam'),
            'button_type_finish' => __('exam.button_type_finish'),
            'show_answer_discussion' => __('exam.show_answer_discussion'),
            'exam_status' => __('exam.exam_status'),
            'total_tolerance' => __('exam.total_tolerance'),
            'price_type' => __('exam.price_type'),
            'price_before_discount' => __('exam.price_before_discount'),
            'price_after_discount' => __('exam.price_after_discount'),
        ];
    }
}
