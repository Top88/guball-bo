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

    // ฟังก์ชันสรุปผลลัพธ์ W/D/L และคะแนนจาก gain_amount
    $sumStats = function ($preds) {
        return [
            'win'      => $preds->where('result','win')->count(),
            'win_half' => $preds->filter(fn($p)=> in_array($p->result, ['win_half', 'winhalf']))->count(),
            'draw'     => $preds->where('result','draw')->count(),
            'lose'     => $preds->where('result','lose')->count(),
            'points'   => (float) $preds->sum('gain_amount'),
        ];
    };
@endphp

<div>
    <!-- อันดับคะแนน โชว์ 10 รายการ-->
    <div class="container-fluid about py-5">
        <div class="container">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-4">อันดับคะแนน{{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }}</h1>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>อันดับ</th>
                        <th>ชื่อเล่น</th>
                        <th>อัพเดทล่าสุด</th>
                        <th class="d-none d-sm-block mt-0">10 ผลงานล่าสุด</th>
                        <th>ชนะ</th>
                        <th>ชนะครึ่ง</th>
                        <th>เสมอ</th>
                        <th>แพ้</th>
                        <th>คะแนน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_rank_list as $key => $item)
                        @php
                            $currentRank = $loop->iteration + (($top_rank_list->currentPage()-1) * $top_rank_list->perPage());

                            // ในตารางบนสุด $item คือสถิติของผู้ใช้ => ต้องอ้าง $item->user
                            $preds = $filterPreds(optional($item->user)->gameFootBallPrediction ?? collect());
                            $stats = $sumStats($preds);
                        @endphp
                        <tr>
                            <td>{{ $currentRank }}</td>
                            <td>
                                <a class="text-dark">{{ $item->user->nick_name }}</a>
                                @if ($item->user->id === auth()->user()->id)
                                    <span style="color:goldenrod"><i class="fas fa-solid fa-trophy"></i></span>
                                @elseif (count($item->user->targetViewPrediction) > 0)
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="viewPredictionResult('{{ $item->user_id }}')" role="button">
                                        <i class="fas fa-search"></i> ดูผลล่าสุด
                                    </button>
                                @elseif ($item->user->predic_today_count > 0)
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="selectViewPrediction('{{ $item->user_id }}', {{ $currentRank }})" role="button">
                                        <i class="fas fa-search"></i> ใช้เหรียญเงินดูผลล่าสุด
                                    </button>
                                @endif
                            </td>
                            <td><a class="text-dark">{{ date_format(date_create($item->updated_at), 'd-m-Y') }}</a></td>
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
            {{ $top_rank_list->links(data: ['scrollTo' => false]) }}
        </div>

        {{-- ====== รายสัปดาห์ ====== --}}
        <div class="container">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-4">อันดับคะแนน ประจำสัปดาห์ ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
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

                            // ในตารางสัปดาห์ $item เป็น User โดยตรง
                            $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                            $stats = $sumStats($preds);
                        @endphp
                        <tr>
                            <td>{{ $currentRank }}</td>
                            <td>
                                <a class="text-dark">{{ $item->nick_name }}</a>
                                @if ($item->id === auth()->user()->id)
                                    <span style="color:goldenrod"><i class="fas fa-solid fa-trophy"></i></span>
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
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-4">อันดับคะแนน ประจำเดือน ({{ $isStep ? 'บอลสเต็ป' : 'บอลเต็ง' }})</h1>
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

                            // ในตารางเดือน $item เป็น User
                            $preds = $filterPreds($item->gameFootBallPrediction ?? collect());
                            $stats = $sumStats($preds);
                        @endphp
                        <tr>
                            <td>{{ $currentRank }}</td>
                            <td>
                                <a class="text-dark">{{ $item->nick_name }}</a>
                                @if ($item->id === auth()->user()->id)
                                    <span style="color:goldenrod"><i class="fas fa-solid fa-trophy"></i></span>
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
    <!-- Services End -->

    <!-- modal ดูผลทายบอล เตือน -1 เหรียญ -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="view-prediction-modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="">ดูผลล่าสุด</h4>
                    </div>
                    <div class="modal-body">
                        <h5 class="p-2">ใช้ <a class="textred"><i class="fas fa-coins" style="color: silver"></i>
                                {{ $costForPrediction }} เหรียญเงิน</a> เพื่อเข้าดูผลงาน</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        <button wire:click="submitViewPrediction()" class="btn btn-success">
                            <i class="fas fa-coins" style="color: silver"></i> ใช้ {{ $costForPrediction }} เหรียญเงิน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            initializeModals();
        });
        function initializeModals() {
            var modalId = document.getElementById('view-prediction-modal');
            var viewPredictionModal = new bootstrap.Modal(modalId);

            window.addEventListener('open-view-prediction-modal', event => {
                viewPredictionModal.show();
            });

            window.addEventListener('hide-view-prediction-modal', event => {
                viewPredictionModal.hide();
            });

            modalId.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('closeModal');
            });
        }
    </script>
@endpush
