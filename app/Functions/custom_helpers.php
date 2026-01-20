<?php


use App\Methods\UserMethods;
use App\Models\User;
use App\Models\UserDailyBusiness;
use App\Models\UserDailyIncomeStats;
use App\Models\UserIncomeOnHold;
use App\Models\UserIncomeStat;
use App\Models\UserIncomeWallet;
use App\Models\UserKyc;
use App\Models\UserLegBusinessWeek;
use App\Models\UserRewardRank;
use App\Models\UserStop;
use App\Models\UserUsdWallet;
use App\Models\UserWeeklyTeamWithBusinessStats;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SiteSetting;

/*
|--------------------------------------------------------------------------
| SITE SETTING HELPER (MUST BE FIRST)
|--------------------------------------------------------------------------
*/
if (!function_exists('siteSetting')) {
    function siteSetting(string $key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = SiteSetting::pluck('value', 'key')->toArray();
        }

        return $settings[$key] ?? $default;
    }
}


if (!function_exists('multiplyDecimalStrings')) {

    function multipleDecimalStrings(string $firstNumberString, string $secondNumberString, $precision = 6)
    {
        return sprintf('%.' . $precision . 'f', $firstNumberString * $secondNumberString);
    }
}

if (!function_exists('castDecimalString')) {

    function castDecimalString(string $numberString, $precision = 2)
    {
        return sprintf('%.' . $precision . 'f', $numberString);
    }
}

if (!function_exists('addDecimalStrings')) {

    function addDecimalStrings(string $firstNumberString, string $secondNumberString, $precision = 6)
    {
        return sprintf('%.' . $precision . 'f', $firstNumberString + $secondNumberString);
    }
}

if (!function_exists('subDecimalStrings')) {

    function subDecimalStrings(string $firstNumberString, string $secondNumberString, $precision = 6)
    {
        return sprintf('%.' . $precision . 'f', $firstNumberString - $secondNumberString);
    }
}

if (!function_exists('divDecimalStrings')) {

    function divDecimalStrings(string $dividend, string $divisor, $precision = 6)
    {
        return sprintf('%.' . $precision . 'f', $dividend / $divisor);
    }
}


if (!function_exists('generateRefCode')) {

    /**
     * Generate RandomUserName
     *
     * @param int|null $digits
     *
     */
    function generateRefCode(): string
    {
        $prefix = strtoupper(trim(siteSetting('referral_prefix', '')));

        do {
            $number = mt_rand(10000, 99999);

            $refCode = $prefix !== ''
                ? $prefix . $number
                : (string) $number;

        } while (User::where('ref_code', $refCode)->exists());

        return $refCode;
    }
}


if (!function_exists('isUserActive')) {
    function isUserActive(User $user): bool
    {
        return UserMethods::init($user)->isUserActive();
    }
}

if (!function_exists('haveActiveSubscriptions')) {
    function haveActiveSubscriptions(User $user): bool
    {
        return $user->subscriptions()->where('is_active', true)->count();
    }
}
if (!function_exists('userIncomeOnHold')) {
    function userIncomeOnHold(User $user): UserIncomeOnHold
    {
        return UserIncomeOnHold::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total' => castDecimalString('0', 2),
                'direct' => castDecimalString('0', 2),
                'level' => castDecimalString('0', 2),
                'roi' => castDecimalString('0', 2),
                'roi_on_roi' => castDecimalString('0', 2),
                'rank' => castDecimalString('0', 2),
                'bonanza' => castDecimalString('0', 2),
                'reward' => castDecimalString('0', 2),
            ]
        );
    }
}

if (!function_exists('userIncomeStat')) {
    function userIncomeStat(User $user): UserIncomeStat
    {
        return UserIncomeStat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total' => castDecimalString('0', 2),
                'direct' => castDecimalString('0', 2),
                'level' => castDecimalString('0', 2),
                'roi' => castDecimalString('0', 2),
                'roi_on_roi' => castDecimalString('0', 2),
                'rank' => castDecimalString('0', 2),
                'bonanza' => castDecimalString('0', 2),
                'reward' => castDecimalString('0', 2),
            ]
        );
    }
}



if (!function_exists('userDailyIncomeStat')) {
    function userDailyIncomeStat(User $user, string $income_date): UserDailyIncomeStats
    {
        return UserDailyIncomeStats::firstOrCreate(
            ['user_id' => $user->id, 'income_date' => $income_date],
            ['income_amount' => castDecimalString('0.00')]
        );
    }
}

if (!function_exists('userDailyBusinessStat')) {
    function userDailyBusinessStat(User $user, string $income_date): UserDailyBusiness
    {
        return UserDailyBusiness::firstOrCreate(
            ['user_id' => $user->id, 'business_date' => $income_date],
            [
                'team_business' => castDecimalString('0.00'),
                'direct_business' => castDecimalString('0.00'),
                'self_business' => castDecimalString('0.00'),
                'is_achieved' => 0,

            ]
        );
    }
}

if (!function_exists('userStop')) {
    function userStop(User $user): UserStop
    {
        return UserStop::firstOrCreate(
            ['user_id' => $user->id],
            [
                'is_blocked' => 0,
                'level' => 0,
                'roi' => 0,
                'roi_on_roi' => 0,
                'maturity_level' => 0,
                'bonanza' => 0,
                'reward' => 0,
                'withdrawal' => 0,
            ]
        );
    }
}

if (!function_exists('userLegBusinessWeek')) {
    function userLegBusinessWeek(User $user, User $legUser, $dateTimeStamp = null): UserLegBusinessWeek
    {
        if (is_null($dateTimeStamp)) {
            $date = now();
            $startOfWeek = $date->startOfWeek()->format('Y-m-d');
            $endOfWeek = $date->endOfWeek()->format('Y-m-d');
        } else {
            $startOfWeek = Carbon::createFromTimestamp($dateTimeStamp)->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::createFromTimestamp($dateTimeStamp)->endOfWeek()->format('Y-m-d');
        }
        return UserLegBusinessWeek::firstOrCreate(
            ['user_id' => $user->id, 'direct_user_id' => $legUser->id, 'start_of_week' => $startOfWeek, 'end_of_week' => $endOfWeek],
            ['usd' => castDecimalString('0', 2)]

        );
    }
}


if (!function_exists('userKyc')) {
    function userKyc(User $user): UserKyc
    {
        return UserKyc::firstOrCreate(
            ['user_id' => $user->id]
        );
    }
}

if (!function_exists('userIncomeWallet')) {
    function userIncomeWallet(User $user): UserIncomeWallet
    {
        return UserIncomeWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => castDecimalString('0', 2), 'balance_on_hold' => castDecimalString('0', 2)]
        );
    }
}

if (!function_exists('userUsdWallet')) {
    function userUsdWallet(User $user): UserUsdWallet
    {
        return UserUsdWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => castDecimalString('0', 2), 'balance_on_hold' => castDecimalString('0', 2)]
        );
    }
}

if (!function_exists('getDepositCoins')) {
    function getDepositCoins()
    {
        return [

            'bep20_usdt' => [
                'symbol' => 'bep20_usdt',
                'price' => fn() => castDecimalString('1', 2)
            ],

        ];
    }

}

if (!function_exists('getDepositCoinPrice')) {
    function getDepositCoinPrice(string $coinSymbol)
    {
        return getDepositCoins()[$coinSymbol]['price']();
    }
}

if (!function_exists('getExplorers')) {
    function getExplorers()
    {
        return config('explorers');
    }
}

if (!function_exists('userRewardRank')) {
    function userRewardRank($user): UserRewardRank
    {
        return UserRewardRank::firstOrCreate(
            ['user_id' => $user->id],
            [
                'rank' => 0,
                'is_achieved' => 0,
                'is_credited' => 0,
            ]
        );
    }
}

if (!function_exists('userWeeklyTeamWithBusinessStat')) {
    function userWeeklyTeamWithBusinessStat($user, $startDate, $endDate): UserWeeklyTeamWithBusinessStats
    {
        return UserWeeklyTeamWithBusinessStats::firstOrCreate(
            ['user_id' => $user->id, 'start_date' => $startDate, 'end_date' => $endDate],
            [
                'direct_active' => 0,
                'team_active' => 0,
                'direct_business' => 0,
                'team_business' => 0,
                'is_pool_active' => 0,
            ]
        );
    }
}


function getDirectUserIds(int $userId): array
{
    return DB::table('user_level_stats')
        ->where('user_id', $userId)
        ->where('level', 1)
        ->pluck('downline_user_id')
        ->toArray();
}

if (!function_exists('siteSetting')) {
    function siteSetting(string $key, $default = null)
    {
        return cache()->remember(
            "site_setting_{$key}",
            now()->addHours(6),
            fn () => DB::table('site_settings')
                ->where('key', $key)
                ->value('value') ?? $default
        );
    }
}



