<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MemberCategory;
use App\Models\MasterData\Category;

class MemberCategoryUser extends Model
{
    use HasFactory;

    protected $table = 'member_category_user';

    protected $fillable = [
        'id',
        'user_id',
        'member_category_id',
        'category_id', 
        'transaction_id', 
        'expired_date',
    ];

    public function memberCategory()
    {
        return $this->belongsTo(MemberCategory::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
