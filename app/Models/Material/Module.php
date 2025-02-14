<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use App\Models\MemberCategory;
use App\Models\Material\DetailModule;
use App\Models\UserAccess;
use Auth;

class Module extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'category_id',
        'title',
        'thumbnail',
        'description',
        'order',
        'price_type', // 1 free, 2 paid
        'price_before_discount',
        'price_after_discount',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class);
    }

    public function detailModule()
    {
        return $this->hasMany(DetailModule::class);
    }

    public function userAccess()
    {
        return $this->morphMany(UserAccess::class, 'access')->where('user_id', Auth::user()->id);
    }
}
