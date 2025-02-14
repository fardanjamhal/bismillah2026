<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class News extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'thumbnail',
        'sort_description',
        'description',
        'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
