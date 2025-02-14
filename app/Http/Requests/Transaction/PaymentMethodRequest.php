<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
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
            'code' => 'required|max:191',
            'name' => 'required|max:191',
            'description' => 'required',
            'show_description' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'Kode',
            'name' => 'Nama',
            'description' => 'Deskripsi',
            'show_description' => 'Tampilkan Deskripsi',
            'is_active' => 'Status',
        ];
    }
}
