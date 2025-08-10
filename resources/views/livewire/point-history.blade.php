@php
    use App\Domain\Football\Match\PredictionResult;
    use Carbon\Carbon;

    // จัดเรียงประวัติจาก "ใหม่ -> เก่า" ตาม key (วันที่)
    $sortedHistories = collect($this->pointHistories ?? [])
        ->sortByDesc(function ($items, $dateKey) {
            try {
                return Carbon::parse($dateKey);
            } catch (\Exception $e) {
                return Carbon::minValue();
            }
        });

    // ดึงเดือนล่าสุดจาก key แรก
    $latestDateKey   = optional($sortedHistories->keys())->first();
    $latestItems     = $latestDateKey ? ($sortedHistories[$latestDateKey] ?? collect()) : collect();
    $latestGoldSum   = (float) $latestItems->sum('gain_amount');
    $latestMonth     = $latestDateKey ? Carbon::parse($latestDateKey)->month : null;
    $latestYear      = $latestDateKey ? Carbon::parse($latestDateKey)->year : null;

    // กรองข้อมูลเฉพาะเดือนล่าสุด
    $currentMonthHistories = $sortedHistories->filter(function ($items, $dateKey) use ($latestMonth, $latestYear) {
        try {
            $date = Carbon::parse($dateKey);
            return $date->month === $latestMonth && $date->year === $latestYear;
        } catch (\Exception $e) {
            return false;
        }
    });
@endphp

<div>
    <!-- ทายผลบอล โชว์เฉพาะเดือนล่าสุด -->
    <div class="container-fluid about py-5">
        <div class="container col-lg-8 col-xs-12">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>ประวัติคะแนน</h1>
            </div>

            <select name="pets" id="pet-select" wire:model.live="searchMonth" class="tick-modal-main-select"
                style="color: #636c79 !important; background: #dee2e6 !important; padding: 10px !important;">
                <option value=""> เลือกเดือนที่ต้องการ </option>
                @foreach ($this->monthList ?? [] as $key => $item)
                    <option value="{{ $key }}"> {{ $item }} </option>
                @endforeach
            </select>

            <div class="table-responsive mt-3">
                <table>
                    <thead>
                        {{-- หัวตาราง: โชว์ข้อมูลล่าสุด --}}
                        <tr>
                            <th>วันที่</th>
                            <th>บอลเต็ง <i class="fas fa-star text-warning"></i></th>
                            <th>บอลสเต็ป</th>
                            <th>เหรียญทอง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($currentMonthHistories as $key => $items)
                            @php
                                $singles = $items->where('type', 'single');
                                $steps   = $items->where('type', 'step');
                                $goldSum = (float) $items->sum('gain_amount');
                            @endphp
                            <tr>
                                <td><strong>{{ $key }}</strong></td>

                                {{-- บอลเต็ง --}}
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        @forelse ($singles as $prediction)
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-star text-warning"></i>
                                                <span>{{ $prediction->team->name ?? '-' }}</span>
                                                {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                            </div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- บอลสเต็ป --}}
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        @forelse ($steps as $prediction)
                                            <div class="d-flex align-items-center gap-1">
                                                <span>{{ $prediction->team->name ?? '-' }}</span>
                                                {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                            </div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- เหรียญทอง --}}
                                <td>
                                    <i class="fas fa-coins text-warning"></i>
                                    {{ number_format($goldSum, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- End -->

    <!-- โชว์คะแนนทั้งหมด -->
    <hr>
    <div class="container p-5">
        <div class="row text-center">
            <div class="col-6">
                <h4>เหรียญทองทั้งหมด</h4>
            </div>
            <div class="col-6">
                <h4 class="textred">
                    <i class="fas fa-coins" style="color: goldenrod"></i>
                    {{ number_format($userGoldCoins, 0) }} คะแนน
                </h4>
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-6">
                <h4>เหรียญเงินทั้งหมด</h4>
            </div>
            <div class="col-6">
                <h4 class="textred">
                    <i class="fas fa-coins" style="color: silver"></i>
                    {{ number_format($userSilverCoins, 0) }} เหรียญ
                </h4>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-4"></div>
            <div class="col-4 mt-3">
                <button type="button" class="btn btn-lg btn-success" data-bs-toggle="modal" href="#modal" role="button">
                    <i class="fas fa-coins"></i> แลกเครดิต
                </button>
            </div>
            <div class="col-4"></div>
        </div>

        <div class="row text-center p-3">
            <h5 class="textred">
                ** {{ number_format(config('settings.exchange_credit_cost', 2000), 0) }} เหรียญทอง
                สามารถแลกได้ {{ number_format(config('settings.exchange_credit_gain_amount', 0), 0) }} เครดิต
            </h5>
        </div>
    </div>

    <!-- modal แลกเหรียญ -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class=""><i class="fas fa-coins"></i> แลกเครดิต</h4>
                    </div>
                    <div class="modal-body">
                        <p>ใช้ {{ number_format(config('settings.exchange_credit_cost', 2000), 0) }} เหรียญทอง แลกเครดิต</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        <button wire:click="submitExchangeCredit()" class="btn btn-success">
                            <i class="fas fa-coins"></i> ยืนยัน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
