<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use \App\Models\Transaction\Transaction;

class UserAccess extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'transaction_id',
        'access_type', // exam, exam_group, module, video_module
        'access_id', // ID dari access terkait
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function access()
    {
        return $this->morphTo(null, 'access_type', 'access_id');
    }
}
