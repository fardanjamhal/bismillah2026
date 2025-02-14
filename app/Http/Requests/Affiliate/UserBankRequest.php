<?php

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class UserBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:191',
            'account_number' => 'required|string|max:191',
            'account_name' => 'required|string|max:30',
        ];
    }

    public function attributes()
    {
        return [
            'bank_name' => __('user_bank.bank_name'),
            'account_number' => __('user_bank.account_number'),
            'account_name' => __('user_bank.account_name'),
        ];
    }
}
