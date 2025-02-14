<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Transaction\Transaction;

class VoucherCode extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'nominal_voucher',
        'user_limit',
        'expired_date',
        'is_active',
    ];

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'item')->withTrashed();
    }
}
