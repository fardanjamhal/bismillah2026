<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Setting\Setting;

class RegisterRequest extends FormRequest
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
        
        $fields = $setting->authentication_field ?? [];

        $rules = [];

        foreach ($fields as $field) {
            if ($field['is_active'] == '1') {
                $fieldName = $field['name'];

                if ($field['is_required'] == '1') {
                    $rules[$fieldName] = 'required';
                    
                    switch ($fieldName) {
                        case 'code':
                            $rules[$fieldName] .= '|string|max:255|unique:users,code';
                            break;
                        case 'name':
                            $rules[$fieldName] .= '|string|max:255';
                            break;
                        case 'email':
                            $rules[$fieldName] .= '|max:255|unique:users';
                            break;
                        case 'phone_number':
                            $rules[$fieldName] .= '|numeric|unique:students';
                            break;
                        case 'username':
                            $rules[$fieldName] .= '|string|max:255|unique:users,username';
                            break;
                        case 'password':
                            $rules[$fieldName] .= '|string|min:8|confirmed';
                            break;
                        case 'province_id':
                        case 'city_id':
                        case 'district_id':
                        case 'village_id':
                        case 'address':
                        case 'gender':
                            break;
                    }
                } else {
                    switch ($fieldName) {
                        case 'email':
                            $rules[$fieldName] = 'nullable|max:255|unique:users';
                            break;
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        $setting = Setting::first();
        $fields = $setting->authentication_field ?? [];
        
        $attributes = [];

        // Mengambil terjemahan untuk setiap field
        foreach ($fields as $field) {
            if ($field['is_active'] == '1') {
                $attributes[$field['name']] = $field['translate'];
            }
        }

        return $attributes;
    }
}
