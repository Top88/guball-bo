@php
    global $loop;
    use App\Domain\Football\Match\PredictionResult;

    // ใช้ตัวแปร $type จากคอมโพเนนต์ เพื่อดูว่าเป็นหน้าเต็งหรือสเต็ป
    $isStep = ($type ?? 'single') === 'step';

    // ฟังก์ชันช่วยกรอง collection ให้เหลือเฉพาะชนิดที่ต้องการ
    $filterPreds = function ($collection) use ($isStep) {
        if (!$collection) return collect();
        $want = $isStep ? 'step' : 'single';
        return $collection->filter(fn($p) => strtolower(trim($p->type ?? '')) === $want);
    };

    // ฟังก์ชันสรุปผลลัพธ์ W/D/L และคะแนนจาก gain_amount (อิง enum PredictionResult)
    $sumStats = function ($preds) {
        return [
            'win'      => $preds->where('result', PredictionResult::WIN->value)->count(),
            'win_half' => $preds->where('result', PredictionResult::HALF->value)->count(),
            'draw'     => $preds->where('result', PredictionResult::DRAW->value)->count(),
            'lose'     => $preds->where('result', PredictionResult::LOSE->value)->count(),
            'points'   => (float) $preds->sum('gain_amount'),
        ];
    };
@endphp

<div>
    <div class="container-fluid about py-5">
        {{-- ====== รายสัปดาห์ ====== --}}
        <div class="container">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>อันดับคะแนน ประจำสัปดาห์ ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>อันดับ</th>
                        <th>ชื่อเล่น</th>
                        <th class="d-none d-sm-block mt-0">10 ผลงานล่าสุด</th>
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
                            $currentRank = $loop->iteration + (($top_rank_list_by_week->currentPage()-1) * $top_rank_list_by_week->perPage());
                            $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                            $stats = $sumStats($preds);
                        @endphp
                        <tr wire:key="wk-{{ $item->id }}-{{ $currentRank }}">
                            <td>{{ $currentRank }}</td>
                            <td>
                                <a class="text-dark">{{ $item->nick_name }}</a>
                                @php $authId = auth()->id(); @endphp

                                @if ($item->id === $authId)
                                      <i class="fas fa-solid fa-trophy trophy-gold"></i>

                                @elseif (!empty($item->targetViewPrediction) && count($item->targetViewPrediction) > 0)
                                    <button type="button" class="btn btn-primary btn-sm"
                                        wire:click.prevent="viewPredictionResult('{{ $item->id }}')">
                                        <i class="fas fa-search"></i> ดูผลล่าสุด
                                    </button>

                                @elseif (!empty($item->predic_today_count) && (int)$item->predic_today_count > 0)
                                    <button type="button" class="btn btn-primary btn-sm"
                                        wire:click.prevent="selectViewPrediction('{{ $item->id }}', {{ $currentRank }})">
                                        <i class="fas fa-search"></i> ใช้เหรียญเงินดูผลล่าสุด
                                    </button>
                                @endif
                            </td>
                            <td class="d-none d-sm-block mt-1">
                                <div style="text-align:center">
                                    @foreach ($preds->take(10) as $prediction)
                                        {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $stats['win'] }}</td>
                            <td>{{ $stats['win_half'] }}</td>
                            <td>{{ $stats['draw'] }}</td>
                            <td>{{ $stats['lose'] }}</td>
                            <td>{{ $stats['points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $top_rank_list_by_week->links(data: ['scrollTo' => false]) }}
        </div>

        {{-- ====== รายเดือน ====== --}}
        <div class="container">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>อันดับคะแนน ประจำเดือน ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>อันดับ</th>
                        <th>ชื่อเล่น</th>
                        <th class="d-none d-sm-block mt-0">10 ผลงานล่าสุด</th>
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
                            $currentRank = $loop->iteration + (($top_rank_list_by_month->currentPage()-1) * $top_rank_list_by_month->perPage());
                            $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                            $stats = $sumStats($preds);
                        @endphp
                        <tr wire:key="mn-{{ $item->id }}-{{ $currentRank }}">
                            <td>{{ $currentRank }}</td>
                            <td>
                                <a class="text-dark">{{ $item->nick_name }}</a>
                                @if ($item->id === auth()->id())
                                      <i class="fas fa-solid fa-trophy trophy-gold"></i>
                                @endif
                            </td>
                            <td class="d-none d-sm-block mt-1">
                                <div style="text-align:center">
                                    @foreach ($preds->take(10) as $prediction)
                                        {!! PredictionResult::toBadge($prediction->result ?? null) !!}
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $stats['win'] }}</td>
                            <td>{{ $stats['win_half'] }}</td>
                            <td>{{ $stats['draw'] }}</td>
                            <td>{{ $stats['lose'] }}</td>
                            <td>{{ $stats['points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $top_rank_list_by_month->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- ===== Modal ใช้เหรียญดูผล (ต้องมีบนหน้านี้) ===== --}}
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

    {{-- ===== Script คุม Modal: ไม่พึ่ง @stack('scripts') ===== --}}
    <script>
    document.addEventListener('livewire:initialized', () => {
        const modalEl = document.getElementById('view-prediction-modal');
        if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) return;

        const viewPredictionModal = new bootstrap.Modal(modalEl);

        // เปิด/ปิดจากฝั่งคอมโพเนนต์
        window.addEventListener('open-view-prediction-modal', () => viewPredictionModal.show());
        window.addEventListener('hide-view-prediction-modal',  () => viewPredictionModal.hide());

        modalEl.addEventListener('hidden.bs.modal', () => {
            if (window.Livewire) Livewire.dispatch('closeModal');
        });
    });
    </script>
</div>
