<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class UserBank extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'bank_name',
        'account_number',
        'account_name',
    ];
}
