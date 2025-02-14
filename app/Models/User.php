<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\MasterData\Student;
use App\Models\MemberCategory;
use App\Models\MemberCategoryUser;
use App\Models\UserAccess;
use App\Models\Affiliate\ReferralLink;
use App\Models\Affiliate\UserCommission;
use App\Models\Transaction\Transaction;
use App\Models\Affiliate\Commission;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'referrer_id',
        'code',
        'name',
        'email',
        'username',
        'password',
        'member_type',
        'level',
        'is_active',
        'account_balance',
        'expiry_verification_date',
        //referrer
        'is_referrer',
        'commission_type',
        'commission',
        'last_login_at',
        'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

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

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    protected function lastLoginAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == NULL ? '-' : Carbon::parse($value)->diffForHumans()
        );
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class, 'member_category_user')->withPivot('category_id', 'transaction_id', 'expired_date')->withTimestamps();
    }

    public function memberCategoryUser()
    {
        return $this->hasMany(MemberCategoryUser::class);
    }

    public function userAccess()
    {
        return $this->hasMany(UserAccess::class)->orderBy('created_at', 'DESC');
    }

    public function activeSessionsCount()
    {
        $activeSessions = DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
            ->count();

        return $activeSessions;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    public function referralLink()
    {
        return $this->hasOne(ReferralLink::class);
    }

    public function userCommission()
    {
        return $this->hasOne(UserCommission::class);
    }

    public function commission()
    {
        return $this->hasMany(Commission::class);
    }

    public function grade()
    {
        return $this->hasMany(\App\Models\Exam\Grade::class);
    }

    public function transactionReferrals()
    {
        return $this->hasMany(Transaction::class)->where('transaction_status', 'done')->where('item_type', '<>', 'TopupBalance');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
