<?php

namespace App\Http\Requests\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class AffiliateRequest extends FormRequest
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
            'enable_affiliate_feature' => 'required|in:0,1',
            'show_affiliator' => 'required|in:0,1',
            'terms_and_condition' => 'required',
            'commission_type' => 'required|in:1,2',
            'commission' => 'required',
            'minimum_withdrawal' => 'required',
            'admin_fee' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'enable_affiliate_feature' => __('setting.enable_affiliate_feature'),
            'show_affiliator' => __('setting.show_affiliator'),
            'terms_and_condition' => __('setting.terms_and_condition'),
            'commission_type' => __('setting.commission_type'),
            'commission' => __('setting.commission'),
            'minimum_withdrawal' => __('setting.minimum_withdrawal'),
            'admin_fee' => __('setting.admin_fee'),
        ];
    }
}
