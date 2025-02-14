<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Traits\Uuid;

class Commission extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'transaction_id',
        'user_id',
        'amount',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
