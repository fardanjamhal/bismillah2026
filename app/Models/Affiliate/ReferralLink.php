<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ReferralLink extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id', 
        'referral_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}