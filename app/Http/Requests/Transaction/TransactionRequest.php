<?php

namespace App\Http\Requests\Transaction;

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
            'item_type' => 'required|in:0,1,2,3',
            'category_id' => 'required_unless:item_type,3',
            'total_payment' => 'required_if:item_type,3',
            'voucher_id' => 'required_if:item_type,2',
            'lesson_category_id' => 'required_if:item_type,0|required_if:item_type,1',
            'lesson_id' => 'required_if:item_type,0',
            'exam_id' => 'required_if:item_type,0',
            'exam_group_id' => 'required_if:item_type,1',
            'user_id' => 'required',
            'payment_method' => 'required',
            'reduce_account_balance' => 'required_if:payment_method,account_balance',
            'transaction_status' => 'required|in:pending,paid,failed,done,expired',
        ];
    }

    public function attributes()
    {
        return [
            'item_type' => 'Jenis Transaksi',
            'user_id' => 'Peserta',
            'category_id' => 'Kategori Peminatan',
            'total_payment' => 'Nominal Saldo',
            'voucher_id' => 'Voucher Membership',
            'lesson_category_id' => 'Kategori Mata Pelajaran',
            'lesson_id' => 'Mata Pelajaran',
            'exam_id' => 'Latihan Soal',
            'exam_group_id' => 'Tryout',
            'payment_method' => 'Metode Pembayaran',
            'reduce_account_balance' => 'Potong Saldo Akun',
            'transaction_status' => 'Status Transaksi',
        ];
    }
}
