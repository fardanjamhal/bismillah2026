<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'enable_practice_question_sales' => 'required|in:0,1',
            'practice_question_sales_type' => 'required|in:0,1,2,3',
            'enable_tryout_sales' => 'required|in:0,1',
            'tryout_sales_type' => 'required|in:0,1,2,3',
            'enable_module_material_sales' => 'required|in:0,1',
            'module_material_sales_type' => 'required|in:0,1,2,3',
            'enable_video_module_sales' => 'required|in:0,1',
            'video_module_sales_type' => 'required|in:0,1,2,3',
            'enable_course_sales' => 'required|in:0,1',
            'course_sales_type' => 'required|in:0,1,2,3',
            'payment_methods' => 'required',
            'payment_gateway_mode' => 'required',
            'minimum_topup_balance' => 'nullable|numeric',
            'add_transaction_notification' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $paymentMethods = $this->input('payment_methods');

            // Periksa apakah salah satu code adalah "account_balance"
            $hasAccountBalance = collect($paymentMethods)->contains(function ($method) {
                return isset($method['code']) && $method['code'] === 'account_balance';
            });

            // Jika "account_balance" ada, maka minimum_topup_balance wajib diisi
            if ($hasAccountBalance && empty($this->input('minimum_topup_balance'))) {
                $validator->errors()->add(
                    'minimum_topup_balance',
                    __('validation.required', ['attribute' => __('setting.minimum_topup_balance')])
                );
            }
        });
    }

    public function attributes()
    {
        return [
            'enable_practice_question_sales' => __('setting.enable_practice_question_sales'),
            'practice_question_sales_type' => __('setting.practice_question_sales_type'),
            'enable_tryout_sales' => __('setting.enable_tryout_sales'),
            'tryout_sales_type' => __('setting.tryout_sales_type'),
            'enable_module_material_sales' => __('setting.enable_module_material_sales'),
            'module_material_sales_type' => __('setting.module_material_sales_type'),
            'enable_video_module_sales' => __('setting.enable_video_module_sales'),
            'video_module_sales_type' => __('setting.video_module_sales_type'),
            'enable_course_sales' => __('setting.enable_course_sales'),
            'course_sales_type' => __('setting.course_sales_type'),
            'payment_methods' => __('setting.payment_methods'),
            'payment_gateway_mode' => __('setting.payment_gateway_mode'),
            'add_transaction_notification' => __('setting.add_transaction_notification'),
            'minimum_topup_balance' => __('setting.minimum_topup_balance'),
        ];
    }
}
