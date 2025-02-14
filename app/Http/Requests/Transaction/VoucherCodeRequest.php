<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class VoucherCodeRequest extends FormRequest
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
            'code' => request()->isMethod('PUT') ? 'required|max:191|unique:voucher_codes,code,'.request()->id : 'required|max:191|unique:voucher_codes,code',
            'nominal_voucher' => 'required',
            'user_limit' => 'sometimes',
            'expired_date' => 'sometimes',
            'is_active' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'code' => __('voucher_code.code'),
            'nominal_voucher' => __('voucher_code.nominal_voucher'),
            'user_limit' => __('voucher_code.user_limit'),
            'expired_date' => __('voucher_code.expired_date'),
            'is_active' => __('voucher_code.is_active'),
        ];
    }
}
