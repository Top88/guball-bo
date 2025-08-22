<?php

namespace App\Domain\Football\Bonus;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class StepBonusCalculator
{
    public function compute($predictions, array $groupFields = ['step_group_key','step_group_id','group_key','ticket_id']): float
    {
        // รับ Collection|array|null แล้วแปลงเป็น Collection
        $predictions = $predictions instanceof Collection ? $predictions : collect($predictions ?? []);
        if ($predictions->isEmpty()) return 0.0;

        // เอาเฉพาะ type = step
        $preds = $predictions->filter(fn($p) => strtolower(trim((string)($p->type ?? ''))) === 'step')->values();
        if ($preds->isEmpty()) return 0.0;

        // เลือก field สำหรับจัดกลุ่มที่ "ครอบคลุมมากที่สุด" (มีค่า >= 3 รายการ)
        $groupField = null;
        $bestCount  = 0;
        foreach ($groupFields as $f) {
            $count = $preds->filter(fn($p) => $this->hasVal($p, $f))->count();
            if ($count >= 3 && $count > $bestCount) {
                $bestCount  = $count;
                $groupField = $f;
            }
        }

        try {
            $groups = collect();

            if ($groupField) {
                // กลุ่มที่มีคีย์
                $withKey = $preds->filter(fn($p) => $this->hasVal($p, $groupField))
                                 ->groupBy($groupField)
                                 ->values(); // -> Collection<Collection>

                // ที่เหลือไม่มีคีย์ -> เรียงเวลาแล้ว chunk(3)
                $withoutKey = $preds->reject(fn($p) => $this->hasVal($p, $groupField))
                                    ->sort(fn($a, $b) => ($this->ts($a->created_at ?? null) <=> $this->ts($b->created_at ?? null))
                                        ?: (((int)($a->id ?? 0)) <=> ((int)($b->id ?? 0))))
                                    ->values()
                                    ->chunk(3)
                                    ->values();

                $groups = $withKey->merge($withoutKey);
            } else {
                // ไม่มีคีย์ใดใช้ได้กับข้อมูลพอ -> จับชุดละ 3 ตามลำดับเวลา
                $groups = $preds->sort(fn($a, $b) => ($this->ts($a->created_at ?? null) <=> $this->ts($b->created_at ?? null))
                        ?: (((int)($a->id ?? 0)) <=> ((int)($b->id ?? 0))))
                    ->values()
                    ->chunk(3)
                    ->values();
            }

            $bonus = 0.0;
            foreach ($groups as $g) {
                if ($g->count() !== 3) continue;

                // ชนะเต็มทั้ง 3 เท่านั้น
                $allWin = $g->every(fn($p) => strtolower(trim((string)($p->result ?? ''))) === 'win');
                if (!$allWin) continue;

                // โบนัส = ผลรวม gain_amount ของชุดนั้น (ถ้าผลรวมน้อย/ว่าง ใช้ fallback = 300)
                $sum = (float) $g->sum(fn($p) => (float) ($p->gain_amount ?? 0));
                $bonus += ($sum > 0 ? $sum : 300.0);
            }

            return $bonus;
        } catch (\Throwable $e) {
            Log::warning('StepBonusCalculator compute failed', [
                'err'        => $e->getMessage(),
                'groupField' => $groupField,
                'count'      => $preds->count(),
            ]);
            return 0.0;
        }
    }

    private function hasVal($p, string $f): bool
    {
        return isset($p->{$f}) && $p->{$f} !== null && $p->{$f} !== '';
    }

    private function ts($val): int
    {
        if ($val instanceof \Carbon\Carbon) return $val->getTimestamp();
        if ($val instanceof \DateTimeInterface) return $val->getTimestamp();
        if (is_string($val)) {
            $t = strtotime($val);
            return $t !== false ? $t : 0;
        }
        return 0;
    }
}
