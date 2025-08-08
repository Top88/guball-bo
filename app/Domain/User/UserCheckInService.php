<?php

namespace App\Domain\User;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Exceptions\UserNotFoundException;
use App\Infrastructure\Coins\SilverCoinHistoryFactory;
use App\Jobs\CreateUserSilverCoinLogJob;
use App\Models\CheckInSetting;
use App\Models\User;
use App\Models\UserCheckIn;
use Carbon\Carbon;
use DB;
use ErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserCheckInService
{
    public function checkIn(
        string $userId,
        int $day,
    ): string {
        /** @var User $user */
        $user = User::find($userId);
        if ($user === null) {
            throw new UserNotFoundException;
        }
        DB::beginTransaction();
        $current = $user->coins_silver;
        $change = 0;
        $balance = 0;
        $result = '';
        try {
            $result = UserCheckIn::create(['user_id' => $user->id, 'checked_date' => Carbon::now()->toDateTimeString()]);
            if (! $result) {
                throw new ErrorException('Cannot Check In');
            }
            $reward = CheckInSetting::where('day', $day)->first();
            if (! $reward) {
                throw new NotFoundHttpException('Reward not found.');
            }
            $result = $reward->reward_amount.' เหรียญ';
            $change = $reward->reward_amount;
            $user->increment('coins_silver', $change);
            $user->refresh();
            $balance = $user->coins_silver;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $preCreateLog = SilverCoinHistoryFactory::willCreate(
            $user->id,
            SilverCoinHistroryAction::DAILY_CHECK_IN,
            $current,
            $change,
            $balance,
            $user->id,
        );
        CreateUserSilverCoinLogJob::dispatch($preCreateLog);

        return $result;
    }
}
