<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Material\Module;

class DetailModule extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'module_id',
        'title',
        'link',
        'description',
        'order',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
