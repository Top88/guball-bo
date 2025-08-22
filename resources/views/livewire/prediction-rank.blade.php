@php
    global $loop;
    use App\Domain\Football\Match\PredictionResult;

    $isStep = ($type ?? 'single') === 'step';

    $filterPreds = function ($collection) use ($isStep) {
        if (!$collection) return collect();
        $want = $isStep ? 'step' : 'single';
        return $collection->filter(fn($p) => strtolower(trim($p->type ?? '')) === $want);
    };

    $sumStats = function ($preds) {
        return [
            'win'      => $preds->where('result', PredictionResult::WIN->value)->count(),
            'win_half' => $preds->where('result', PredictionResult::HALF->value)->count(),
            'draw'     => $preds->where('result', PredictionResult::DRAW->value)->count(),
            'lose'     => $preds->where('result', PredictionResult::LOSE->value)->count(),
            'points'   => (float) $preds->sum('gain_amount'), // แต้มฐาน (ไม่รวมโบนัส)
        ];
    };
@endphp

<div>
    <div class="container-fluid about py-5">
        {{-- ====== รายสัปดาห์ ====== --}}
        <div class="container mb-5">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>อันดับคะแนน ประจำสัปดาห์ ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
                <p class="small text-white">
                    *** เริ่มนับผลอันดับคะแนนใหม่ทุกวันอังคาร
                </p>
            </div>

            {{-- ค้นหา รายสัปดาห์ --}}
            <div class="row g-2 align-items-center mb-3">
                <div class="col-12 col-md-6">
                    <input
                        type="text"
                        id="searchWeekInput"
                        class="form-control"
                        placeholder="ค้นหาชื่อเล่น - รายสัปดาห์"
                        wire:model.debounce.500ms="searchWeek"
                        aria-label="ค้นหาอันดับรายสัปดาห์"
                    >
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="button" class="btn btn-primary" wire:click="applySearchWeek">
                        <i class="fas fa-search"></i> ค้นหา
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="$set('searchWeek','')">
                        ล้าง
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr class="text-nowrap">
                            <th>อันดับ</th>
                            <th>ชื่อเล่น</th>
                            <th class="d-none d-sm-table-cell">10 ผลงานล่าสุด</th>
                            <th>ชนะ</th>
                            <th>ชนะครึ่ง</th>
                            <th>เสมอ</th>
                            <th>แพ้</th>
                            <th>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top_rank_list_by_week as $key => $item)
                            @php
                                $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                                $stats = $sumStats($preds);
                                // แต้มที่ใช้จัดอันดับจากคอมโพเนนต์ (ฐาน+โบนัสถ้า type=step), fallback เป็นฐาน
                                $displayPoints = isset($item->points) ? (float) $item->points : (float) $stats['points'];

                                // ลำดับจริงที่คอมโพเนนต์คำนวณมาให้
                                $realRank = $item->rank_real ?? null;
                                // fallback กันพลาด
                                $fallbackRank = $loop->iteration + (($top_rank_list_by_week->currentPage()-1) * $top_rank_list_by_week->perPage());
                                $showRank = $realRank ?: $fallbackRank;
                            @endphp
                            <tr wire:key="wk-{{ $item->id }}-{{ $showRank }}">
                                <td>{{ $showRank }}</td>
                                <td class="text-nowrap">
                                    <a class="text-dark">{{ $item->nick_name }}</a>
                                    @php $authId = auth()->id(); @endphp
                                    @if ($item->id === $authId)
                                        <span class="ms-1" style="color:goldenrod"><i class="fas fa-trophy"></i></span>
                                    @elseif (!empty($item->targetViewPrediction) && count($item->targetViewPrediction) > 0)
                                        <button type="button" class="btn btn-primary btn-sm ms-2"
                                            wire:click.prevent="viewPredictionResult('{{ $item->id }}')">
                                            <i class="fas fa-search"></i> ดูผลล่าสุด
                                        </button>
                                    @elseif (!empty($item->predic_today_count) && (int)$item->predic_today_count > 0)
                                        {{-- ส่ง "อันดับจริง" เข้าไป ให้ระบบคิดค่าสิทธิ์ได้ถูกต้อง --}}
                                        <button type="button" class="btn btn-primary btn-sm ms-2"
                                            wire:click.prevent="selectViewPrediction('{{ $item->id }}', {{ $showRank }})">
                                            <i class="fas fa-search"></i> ใช้เหรียญเงินดูผลล่าสุด
                                        </button>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <div class="text-center">
                                        @foreach ($preds->take(10) as $prediction)
                                            {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $stats['win'] }}</td>
                                <td>{{ $stats['win_half'] }}</td>
                                <td>{{ $stats['draw'] }}</td>
                                <td>{{ $stats['lose'] }}</td>
                                <td>{{ number_format($displayPoints, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $top_rank_list_by_week->links(data: ['scrollTo' => false]) }}
        </div>

        {{-- ====== รายเดือน ====== --}}
        <div class="container">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>อันดับคะแนน ประจำเดือน ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
            </div>

            {{-- ค้นหา รายเดือน --}}
            <div class="row g-2 align-items-center mb-3">
                <div class="col-12 col-md-6">
                    <input
                        type="text"
                        id="searchMonthInput"
                        class="form-control"
                        placeholder="ค้นหาชื่อเล่น - รายเดือน"
                        wire:model.debounce.500ms="searchMonth"
                        aria-label="ค้นหาอันดับรายเดือน"
                    >
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="button" class="btn btn-primary" wire:click="applySearchMonth">
                        <i class="fas fa-search"></i> ค้นหา
                    </button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="$set('searchMonth','')">
                        ล้าง
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr class="text-nowrap">
                            <th>อันดับ</th>
                            <th>ชื่อเล่น</th>
                            <th class="d-none d-sm-table-cell">10 ผลงานล่าสุด</th>
                            <th>ชนะ</th>
                            <th>ชนะครึ่ง</th>
                            <th>เสมอ</th>
                            <th>แพ้</th>
                            <th>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top_rank_list_by_month as $key => $item)
                            @php
                                $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                                $stats = $sumStats($preds);
                                $displayPoints = isset($item->points) ? (float) $item->points : (float) $stats['points'];

                                $realRank = $item->rank_real ?? null;
                                $fallbackRank = $loop->iteration + (($top_rank_list_by_month->currentPage()-1) * $top_rank_list_by_month->perPage());
                                $showRank = $realRank ?: $fallbackRank;
                            @endphp
                            <tr wire:key="mn-{{ $item->id }}-{{ $showRank }}">
                                <td>{{ $showRank }}</td>
                                <td class="text-nowrap">
                                    <a class="text-dark">{{ $item->nick_name }}</a>
                                    @if ($item->id === auth()->id())
                                        <span class="ms-1" style="color:goldenrod"><i class="fas fa-trophy"></i></span>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <div class="text-center">
                                        @foreach ($preds->take(10) as $prediction)
                                            {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $stats['win'] }}</td>
                                <td>{{ $stats['win_half'] }}</td>
                                <td>{{ $stats['draw'] }}</td>
                                <td>{{ $stats['lose'] }}</td>
                                <td>{{ number_format($displayPoints, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $top_rank_list_by_month->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- ===== Modal ใช้เหรียญดูผล ===== --}}
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="view-prediction-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ดูผลล่าสุด</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="p-2">
                            ใช้
                            <span class="textred">
                                <i class="fas fa-coins" style="color: silver"></i>
                                {{ $costForPrediction }} เหรียญเงิน
                            </span>
                            เพื่อเข้าดูผลงาน
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" wire:click="submitViewPrediction()" class="btn btn-success">
                            <i class="fas fa-coins" style="color: silver"></i>
                            ใช้ {{ $costForPrediction }} เหรียญเงิน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Script คุม Modal ===== --}}
    <script>
    document.addEventListener('livewire:initialized', () => {
        const modalEl = document.getElementById('view-prediction-modal');
        if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) return;

        const viewPredictionModal = new bootstrap.Modal(modalEl);

        window.addEventListener('open-view-prediction-modal', () => viewPredictionModal.show());
        window.addEventListener('hide-view-prediction-modal',  () => viewPredictionModal.hide());

        modalEl.addEventListener('hidden.bs.modal', () => {
            if (window.Livewire && Livewire.emit) {
                Livewire.emit('closeModal');
            }
        });
    });
    </script>
</div>
