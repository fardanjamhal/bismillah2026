<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\User;

class UserCommission extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'total_commission',
        'total_withdrawals',
        'current_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
