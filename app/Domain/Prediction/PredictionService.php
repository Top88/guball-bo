<?php

namespace App\Domain\Prediction;

use App\Exceptions\CreditPerDayExceedException;
use App\Exceptions\DuplicatePredictionException;
use App\Models\GameFootballPrediction;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Log;

class PredictionService
{
    public function matchPrediction(string $userId, PredictionCollection $predictions): bool
    {
        /**
         * @var User $user
         */
        $user = User::find($userId);
        if ($user === null) {
            return false;
        }

        // ตรวจสอบว่าเกินจำนวนครั้งต่อวันหรือไม่
        $checkCurrentCredit = GameFootballPrediction::where('user_id', $user->id)
            ->where('type', $predictions->first()?->getType() ?? 'step') // ✅ เพิ่มเงื่อนไข type
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->get();

        if ((count($checkCurrentCredit) + $predictions->count()) > config('settings.max_prediction_per_day')) {
            throw new CreditPerDayExceedException;
        }

        // ตรวจสอบว่าทายซ้ำใน match เดิมหรือยัง
        $matchIds = collect($predictions->toArray())->pluck('match_id');
        $checkSameMatch = GameFootballPrediction::where('user_id', $user->id)
            ->where('type', $predictions->first()?->getType() ?? 'step') // ✅ ตรงนี้ด้วย
            ->whereIn('match_id', $matchIds)->get();

        if ($checkSameMatch->count() > 0) {
            throw new DuplicatePredictionException;
        }

        DB::beginTransaction();
        try {
            foreach ($predictions->toArray() as $value) {
                // 🔧 เพิ่ม type เป็นค่า default หากไม่มีมา
                if (!isset($value['type'])) {
                    $value['type'] = 'step';
                }

                GameFootballPrediction::create(array_filter($value));
            }

            DB::commit();
            return true;
        } catch (\Exception $th) {
            Log::error($th);
            DB::rollBack();
        }

        return false;
    }
}
