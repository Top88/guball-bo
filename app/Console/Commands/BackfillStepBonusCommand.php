<?php

namespace App\Console\Commands;

use App\Domain\Coin\Gold\StepBonusPayoutService;
use App\Models\GameFootballPrediction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BackfillStepBonusCommand extends Command
{
    protected $signature = 'app:backfill-step-bonus
                            {--from= : YYYY-MM-DD เริ่ม}
                            {--to=   : YYYY-MM-DD ถึง}
                            {--user= : user id (UUID) เฉพาะรายนี้}';

    protected $description = 'คำนวณและให้เครดิตโบนัสสเต็ปครบ 3 (W ครบชุด) ย้อนหลังแบบกันซ้ำ (รองรับ UUID)';

    public function handle(StepBonusPayoutService $service): int
    {
        $from = $this->option('from') ? Carbon::parse($this->option('from'))->startOfDay() : now()->startOfMonth();
        $to   = $this->option('to')   ? Carbon::parse($this->option('to'))->endOfDay()     : now()->endOfDay();

        $specificUserId = $this->option('user'); // อาจเป็น UUID

        // ถ้าไม่ระบุ user -> หา user ทั้งหมดที่มีรายการในช่วงเวลา (จะได้เป็น UUID)
        $userIds = $specificUserId
            ? collect([(string) $specificUserId])
            : GameFootballPrediction::query()
                ->whereBetween('created_at', [$from, $to])
                ->distinct()
                ->pluck('user_id')       // pluck ออกมาเป็น string (UUID)
                ->map(fn ($id) => (string) $id);

        $this->info("Backfill from {$from->toDateString()} to {$to->toDateString()} for {$userIds->count()} user(s)");

        $total = 0;
        foreach ($userIds as $uid) {
            $credited = $service->creditRange((string) $uid, $from->copy(), $to->copy()); // ✅ ไม่แคสต์เป็น int
            $this->line(" - user {$uid}: +{$credited}");
            $total += $credited;
        }

        $this->info("Done. Total credited: {$total}");
        return self::SUCCESS;
    }
}
