<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Setting\Setting;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        return new User([
            'name' => $row['nama'],
            'code' => $row['kode'],
            'email' => $row['email'],
            'username' => $row['username'],
            'password' => bcrypt($row['password']),
            'member_type' => $row['tipe_member'],
            'level' => $row['level'],
            'is_active' => $row['status'],
        ]);
    }

    public function rules(): array
    {
        $setting = Setting::first();
        $fields = $setting->authentication_field ?? [];

        $rules = [];

        foreach ($fields as $field) {
            if ($field['is_active'] == '1') {
                $fieldName = $field['name'];
                $isRequired = $field['is_required'] == '1';
                $rule = $isRequired ? 'required' : 'nullable';

                switch ($fieldName) {
                    case 'code':
                        $rules['*.kode'] = [$rule, 'max:255', 'unique:users,code'];
                        break;
                    case 'name':
                        $rules['*.nama'] = [$rule, 'string', 'max:255'];
                        break;
                    case 'email':
                        $rules['*.email'] = [$rule, 'max:255', 'email', 'unique:users,email'];
                        break;
                    case 'username':
                        $rules['*.username'] = [$rule, 'string', 'max:255', 'unique:users,username'];
                        break;
                    case 'password':
                        $rules['*.password'] = [$rule];
                        break;
                }
            }
        }

        $rules['*.tipe_member'] = 'required|in:1,2';
        $rules['*.level'] = 'required|in:1,2,3';
        $rules['*.status'] = 'required|in:0,1';

        return $rules;
    }

    public function customValidationMessages()
    {
        return [
            '*.nama.required' => 'Nama Wajib Diisi',
            '*.email.required' => 'Email Wajib Diisi',
            '*.email.email' => 'Format Email Tidak Valid',
            '*.email.unique' => 'Email Sudah Terdaftar',
            '*.kode.required' => 'Kode Wajib Diisi',
            '*.kode.unique' => 'Kode Sudah Terdaftar',
            '*.username.required' => 'Username Wajib Diisi',
            '*.username.unique' => 'Username Sudah Terdaftar',
            '*.password.required' => 'Password Wajib Diisi',
            '*.level.required' => 'Level Wajib Diisi',
            '*.level.in' => 'Level Tidak Valid',
            '*.tipe_member.required' => 'Tipe Member Wajib Diisi',
            '*.tipe_member.in' => 'Tipe Member Tidak Valid',
            '*.status.required' => 'Status Wajib Diisi',
        ];
    }
}
