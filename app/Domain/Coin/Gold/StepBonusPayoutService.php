<?php

namespace App\Domain\Coin\Gold;

use App\Domain\Football\Bonus\StepBonusCalculator;
use App\Models\GameFootballPrediction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StepBonusPayoutService
{
    public const ACTION = 'bonus_step3_fullwin'; // ชื่อ action ใน log

    public function __construct(private StepBonusCalculator $bonusCalc) {}

    /**
     * จ่ายโบนัสให้ผู้ใช้ 1 คนใน "วันที่" เดียว (รองรับ UUID และกันซ้ำด้วย unique_key)
     */
    public function creditDaily(string $userId, Carbon $date): int
    {
        $from = $date->copy()->startOfDay();
        $to   = $date->copy()->endOfDay();

        $preds = GameFootballPrediction::query()
            ->where('user_id', $userId) // UUID
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $bonus = (int) $this->bonusCalc->compute($preds); // 0, 300, 600, ...
        if ($bonus <= 0) return 0;

        $uniqueKey = sprintf('step-bonus:%s:%s', $userId, $from->toDateString());

        return DB::transaction(function () use ($userId, $bonus, $uniqueKey, $from) {
            // ยอดก่อนทำรายการ (ถ้าไม่มี row ให้ถือเป็น 0)
            $current = (int) (DB::table('users')->where('id', $userId)->value('coins_gold') ?? 0);

            // กันเครดิตซ้ำด้วย unique_key
            $inserted = DB::table('user_gold_coin_transaction_log')->insertOrIgnore([[
                'user_id'   => $userId,                 // UUID
                'action'    => self::ACTION,
                'game_type' => 'football_match',
                'game_id'   => null,
                'current'   => $current,
                'change'    => $bonus,
                'balance'   => null,                    // อัปเดตทีหลัง
                'note'      => $from->toDateString(),   // เก็บวันที่ที่คำนวณโบนัส
                'unique_key'=> $uniqueKey,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]]);

            if ($inserted === 0) {
                // เคยให้โบนัสวันนี้ไปแล้ว
                return 0;
            }

            // เพิ่มเหรียญให้ผู้ใช้ (UUID)
            DB::table('users')->where('id', $userId)->increment('coins_gold', $bonus);

            // อ่านยอดใหม่มาอัปเดต balance ใน log ให้ตรง
            $after = (int) (DB::table('users')->where('id', $userId)->value('coins_gold') ?? 0);

            DB::table('user_gold_coin_transaction_log')
                ->where('unique_key', $uniqueKey)
                ->update(['balance' => $after]);

            return $bonus;
        });
    }

    /**
     * จ่ายโบนัสให้ผู้ใช้ 1 คน ครอบคลุมช่วงวัน [from..to]
     */
    public function creditRange(string $userId, Carbon $from, Carbon $to): int
    {
        $total  = 0;
        $cursor = $from->copy()->startOfDay();
        $end    = $to->copy()->endOfDay();

        while ($cursor->lte($end)) {
            $total += $this->creditDaily($userId, $cursor);
            $cursor->addDay();
        }

        return $total;
    }
}
