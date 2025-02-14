<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    public $incrementing = false;
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'show_description',
        'is_active',
    ];
}
