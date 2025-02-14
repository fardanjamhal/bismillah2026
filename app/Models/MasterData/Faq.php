<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Faq extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'question',
        'answer',
    ];

    public function serializeDate($date): ?string
    {
        return ($date != null) ?  $date : null;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y H:i:s')
        );
    }

    public function getAnswerAttribute($value)
    {
        return str_replace("/storage/upload_files/questions","/storage/upload_files/questions", $value);
    }
}
