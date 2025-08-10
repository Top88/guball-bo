@php
    use Carbon\Carbon;
    use App\Helper\ThaiDate;
    use App\Domain\Football\Match\PredictionResult;

    // รับชนิดจากพารามิเตอร์ type (single|step)
    $viewType = strtolower(request('type', 'single'));
    $viewType = in_array($viewType, ['single', 'step']) ? $viewType : 'single';
    $isSingleView = $viewType === 'single';

    // เรียงประวัติให้ "วันที่ล่าสุดอยู่ด้านบน"
    // รองรับทั้งกรณี key เป็น string (Y-m-d) และกรณีเป็นอย่างอื่น
    $histories = collect($this->pointHistories ?? [])->sortByDesc(function ($items, $key) {
        try {
            return Carbon::parse((string) $key);
        } catch (\Throwable $e) {
            // ถ้าพาร์สไม่ได้ ให้คงเดิม (แต่ sortByDesc ต้อง return บางอย่าง)
            return $key;
        }
    });
@endphp
<div>
    <div class="container-fluid about py-5">
        <div class="container col-lg-5 col-xs-12">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>
                    {{ $this->rank ?? 'ไม่มีอันดับ' }}
                    <i class="fa-solid fa-trophy"></i> {{ $user->nick_name }}
                </h1>
                <h4 class="text-primary">
                    เกมทายผลบอล ({{ $isSingleView ? 'บอลเต็ง' : 'บอลสเต็ป' }}) วันที่
                    {{ ThaiDate::toDateTime(Carbon::now()->toDateTimeString(), isShort: true) }}
                </h4>
            </div>

            {{-- ปุ่มกลับไปหน้าอันดับ พร้อมส่ง type เดิม --}}
            <div class="row">
                <div class="col-12 mt-2 text-center">
                    <a href="{{ route('prediction-rank', ['type' => $viewType]) }}"
                       class="btn btn-primary" style="width: 80%;">
                        อันดับคะแนน
                    </a>
                </div>
            </div>

            <br>

            {{-- ตารางประวัติคะแนน (แสดงเฉพาะ type ที่เลือก) --}}
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>{{ $isSingleView ? 'บอลเต็ง' : 'บอลสเต็ป' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $key => $items)
                            @php
                                $list = collect($items)->where('type', $viewType);
                            @endphp
                            <tr>
                                <td><strong>{{ $key }}</strong></td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        @forelse ($list as $prediction)
                                            <div class="d-flex align-items-center gap-1">
                                                @if($isSingleView)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endif
                                                <span>{{ $prediction->team->name ?? '-' }}</span>
                                                {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                            </div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted">ไม่มีประวัติ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
