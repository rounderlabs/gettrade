<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmail;
use App\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SerializeDateTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'security_answer',
        'mobile',
        'country_id',
        'ref_code',
        'sponsor_id',
        'placed_into_tree',
        'position',
        'email_verified_at',
        'profile_picture',
        'is_blocked',
        'welcome_seen_at',
        'welcome_mode'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_at' => 'datetime',
        'created_at' => 'datetime',
        'placed_into_tree' => 'boolean'
    ];

    public function getJoinedOnAttribute($value)
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F j, Y', strtotime($value));
    }

    public function getActiveOnAttribute($value)
    {
        return $this->active_at ? $this->active_at->format('F j, Y') : null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function tree()
    {
        return $this->hasOne(Tree::class);
    }

    public function binaryTree()
    {
        return $this->hasOne(BinaryTree::class);
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function sponsor()
    {
        return $this->tree->sponsor();
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function userLevels()
    {
        return $this->hasMany(UserLevel::class);
    }

    public function scopeWhereRefCode($query, string $refCode)
    {
        return $query->where('ref_code', $refCode);
    }

    public function userLevelStats()
    {
        return $this->hasMany(UserLevelStat::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }



    public function voucherTransactions()
    {
        return $this->hasMany(VoucherTransaction::class);
    }



    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getTotalSubscriptionAmountAttribute()
    {
        return $this->subscriptions()
            ->where('is_active', true)
            ->sum('amount');
    }

    protected $appends = [
        'total_subscription_amount',
    ];


    public function userLegBusinesses()
    {
        return $this->hasMany(UserLegBusiness::class);
    }

    public function userBusiness()
    {
        return $this->hasOne(UserBusiness::class);
    }

    public function userIncomeWallet()
    {
        return $this->hasOne(UserIncomeWallet::class);
    }

    public function userIncomeStat()
    {
        return $this->hasOne(UserIncomeStat::class);
    }

    public function userIncomeOnHold()
    {
        return $this->hasOne(UserIncomeOnHold::class);
    }

    public function userActive()
    {
        return $this->hasOne(UserActive::class);
    }

    public function userUsdWallet()
    {
        return $this->hasOne(UserUsdWallet::class);
    }


    public function userUsdWalletTransactions()
    {
        return $this->hasMany(UserUsdWalletTransaction::class);
    }


    public function withdrawWallets()
    {
        return $this->hasMany(WithdrawWallet::class);
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function withdrawalTemps()
    {
        return $this->hasMany(WithdrawalTemp::class);
    }

    public function withdrawalHistories()
    {
        return $this->hasMany(WithdrawalHistory::class);
    }

    public function userStop()
    {
        return $this->hasOne(UserStop::class);
    }

    public function userLegBusinessWeeks()
    {
        return $this->hasMany(UserLegBusinessWeek::class);
    }

    public function isPlacedIntoTree(): bool
    {
        return $this->placed_into_tree;
    }

    public function markUserPlacedIntoTree(): bool
    {
        return $this->update([
            'placed_into_tree' => true
        ]);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function userUsdWalletAdminUpdates()
    {
        return $this->hasMany(UserUsdWalletAdminUpdate::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function userDirectIncomes()
    {
        return $this->hasMany(UserDirectIncome::class);
    }

    public function userRoiIncomes()
    {
        return $this->hasMany(UserRoiIncome::class);
    }

    public function userLevelRoiIncomes()
    {
        return $this->hasMany(UserLevelRoiIncome::class);
    }

    public function levelIncomes()
    {
        return $this->hasMany(UserLevelIncome::class);
    }

    public function userLevelIncomeStats(): HasMany
    {
        return $this->hasMany(UserLevelIncomeStat::class);
    }

    public function userWithdrawFrequency()
    {
        return $this->hasOne(UserWithdrawFrequency::class);
    }

    public function brokerage(): HasOne
    {
        return $this->hasOne(Brokerage::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotifications::class);
    }

    public function cryptApiWallets()
    {
        return $this->hasMany(CryptApiWallet::class);
    }


    public function cryptApiTransactions()
    {
        return $this->hasMany(CryptApiTransaction::class);
    }

    public function depositTransactions()
    {
        return $this->hasMany(DepositTransaction::class);
    }

    public function userDailyIncomeStats()
    {
        return $this->hasMany(UserDailyIncomeStats::class);
    }

    public function userLevelAchievementStat()
    {
        return $this->hasOne(UserLevelAchievementStat::class);
    }

    public function userInvestment()
    {
        return $this->hasOne(UserInvestment::class);
    }

    public function userMagicIncomeStats()
    {
        return $this->hasMany(UserMagicIncomeStats::class);
    }

    public function userPoolIncomeStats()
    {
        return $this->hasMany(UserPoolIncomeStats::class);
    }

    public function userRewardIncomeStats()
    {
        return $this->hasMany(UserRewardIncomeStats::class);
    }

    public function userRewardRank()
    {
        return $this->hasMany(UserRewardRank::class);
    }

    public function userDailyBusiness()
    {
        return $this->hasMany(UserDailyBusiness::class);
    }

    public function userWeeklyTeamWithBusinessStats()
    {
        return $this->hasMany(UserWeeklyTeamWithBusinessStats::class);
    }

    public function kyc()
    {
        return $this->hasOne(UserKyc::class);
    }

    public function kycSubmissions()
    {
        return $this->hasMany(KycSubmission::class);
    }

    public function userRankRoiIncomes()
    {
        return $this->hasMany(UserRankRoiIncomes::class);
    }


}
