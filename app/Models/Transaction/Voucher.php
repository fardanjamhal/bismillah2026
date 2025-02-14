<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterData\Category;
use App\Models\MemberCategory;
use App\Models\UserAccess;

class Voucher extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'member_categories' => 'json',
    ];
    
    protected $fillable = [
        'id',
        'category_id',
        'title',
        'active_period',
        'period_type',
        'price_before_discount',
        'price_after_discount',
        'description',
        'hexa_color_background',
        'hexa_color_title',
        'member_categories',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class)->withTimestamps();
    }

    public function userAccess()
    {
        return $this->morphMany(UserAccess::class);
    }
}
