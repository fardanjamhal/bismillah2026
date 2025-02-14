<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Setting\Setting;

class AccountBalanceRequest extends FormRequest
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
        $setting = Setting::first();
        return [
            'top_up_balance' => 'required|numeric|min:'.$setting->minimum_topup_balance ?? 0,
        ];
    }

    public function attributes()
    {
        return [
            'top_up_balance' => 'Nominal Top Up Saldo'
        ];
    }
}
