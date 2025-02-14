<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\Transaction\PaymentConfirmation;
use App\Models\Transaction\Voucher;
use App\Models\Affiliate\Commission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $casts = [
        'member_categories' => 'json',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'referrer_id',
        'category_id',
        'item_type', // exam, exam_group, voucher, bundling
        'item_id', // id item terkait
        'code',
        'description',
        'payment_method',
        'total_payment',
        'payment_file',
        'transaction_status',
        'active_period',
        'period_type',
        'expired_date',
        'snap_token',
        'created_at',
    ];

    public function serializeDate($date): ?string
    {
        return ($date != null) ?  $date : null;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y H:i')
        );
    }

    public static function generateCode()
    {
        $code = 'INV';
        $result = $code.Carbon::now()->format('Ymdhis');

        return $result;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function paymentConfirmation()
    {
        return $this->hasOne(PaymentConfirmation::class);
    }

    public function item()
    {
        return $this->morphTo(null, 'item_type', 'item_id');
    }
}
