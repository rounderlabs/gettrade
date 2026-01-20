<?php

namespace App\Jobs;


use App\Models\User;
use App\Models\UserLegBusiness;
use App\Models\UserUsdWalletTransaction;
use App\Methods\UserLevelMethods;
use App\Services\RankService;
use Carbon\CarbonInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CreateUserBusinessJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $totalBusiness;
    public UserUsdWalletTransaction $userUsdWalletTransaction;

    /**
     * @var false|mixed
     */

    public function __construct(User $user, string $totalBusiness, UserUsdWalletTransaction $userUsdWalletTransaction)
    {
        $this->user = $user->withoutRelations();
        $this->totalBusiness = $totalBusiness;
        $this->userUsdWalletTransaction = $userUsdWalletTransaction->withoutRelations();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = $this->user;
        UserLevelMethods::init($this->user)->eachSponsor(function ($parentUser, $level) use (&$user) {
//            $userBusiness = $parentUser->userBusiness()->firstOrCreate();
//            $userBusiness->increment('usd', $this->totalBusiness);
//            $this->createUserLegBusiness($parentUser, $user, $this->totalBusiness);
//           // $this->updatePoolEligibility($parentUser);
//            $user = $parentUser;

            // 1️⃣ Update total business
            $userBusiness = $parentUser->userBusiness()->firstOrCreate();
            $userBusiness->increment('amount', $this->totalBusiness);

            // 2️⃣ Update leg business
            $this->createUserLegBusiness($parentUser, $user, $this->totalBusiness);

            // ✅ 3️⃣ CHECK RANK AFTER BUSINESS UPDATE
            RankService::evaluate($parentUser);

            // Move up the tree
            $user = $parentUser;
        });


    }

    private function createUserLegBusiness(User $user, User $directUser, string $amountInUsd)
    {
        $userLegBusiness = $user->userLegBusinesses()->firstOrCreate(
            ['direct_user_id' => $directUser->id],
            ['amount' => '0']
        );
        $userLegBusiness->increment('amount', $amountInUsd);
        UpdateUserDailyBusinessJob::dispatch($user, 'team_business',$this->totalBusiness )->delay(now()->addSecond());
    }

    private function isUserActive(User $parent_user): bool
    {
        return isUserActive($parent_user);
    }

    private function getMaxLegBusiness(User $user)
    {
        return UserLegBusiness::select('id', 'usd')->where('user_id', $user->id)->orderByDesc('usd')->first();
    }

    private function updatePoolEligibility(User $user)
    {
        $userBusiness = $user->userBusiness;
        $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(CarbonInterface::SUNDAY);

        $startDate =  $startOfWeek->toDateString();
        $endDate =  $endOfWeek->toDateString();
        $userWeeklyTeamWithBusinessStat = userWeeklyTeamWithBusinessStat($user, $startDate, $endDate);
        if($userBusiness->usd >= 5000 && $userWeeklyTeamWithBusinessStat->direct_active > 0){
            $userWeeklyTeamWithBusinessStat->update(['is_pool_active' => 1]);
        }
    }
}
