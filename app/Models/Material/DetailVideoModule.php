<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Material\VideoModule;

class DetailVideoModule extends Model
{
    use HasFactory, Uuid;
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'video_module_id',
        'title',
        'link',
        'description',
        'order',
    ];
}
