<?php

namespace App\Observers;

use App\Models\GameFootballPrediction;
use App\Domain\Coin\Gold\StepBonusPayoutService;
use Carbon\Carbon;

class GameFootballPredictionObserver
{
    public function updated(GameFootballPrediction $prediction): void
    {
        // ยิงเฉพาะตอนที่คอลัมน์ result เปลี่ยน และมีค่าแล้ว
        if ($prediction->wasChanged('result') && !is_null($prediction->result)) {

            $date = $prediction->created_at instanceof \Illuminate\Support\Carbon
                ? $prediction->created_at->copy()
                : Carbon::parse($prediction->created_at);

            // จ่ายโบนัสสเต็ปครบ 3 ให้ "รายวัน" ของผู้ใช้นั้น
            app(StepBonusPayoutService::class)
                ->creditDaily((string) $prediction->user_id, $date);
        }
    }
}
