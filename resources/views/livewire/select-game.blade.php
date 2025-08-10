@php
    use Carbon\Carbon;
    use App\Helper\ThaiDate;
@endphp

<div>
    <div class="container-fluid about py-5">
        <div class="container col-lg-5 col-xs-12">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1>ทายผลบอลสเต็ป</h1>
                <h4 class="text-primary">
                    @lang('website.general.football_game_prediction') วันที่ {{ ThaiDate::toDateTime(Carbon::now()->toDateTimeString(), isShort: true) }}
                </h4>
            </div>

            <form action="">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 35%;">เจ้าบ้าน</th>
                            <th style="width: 30%;">อัตราต่อรอง</th>
                            <th style="width: 35%;">ทีมเยือน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->matchList as $leagueId => $matches)
                            @php
                                $league = $matches->first()->league;
                                $leagueName = optional($league)->name ?? 'No league';
                                $leagueCountry = optional($league)->country ?? '(No country)';
                            @endphp

                            <tr>
                                <th colspan="4" class="bg-th">{{ $leagueName }} {{ $leagueCountry }}</th>
                            </tr>

                            @foreach ($matches as $match)
                                <tr>
                                    <td>
                                        <div class="radio-item">
                                            <input
                                                wire:model.live="predictions.{{ $match->id }}"
                                                value="{{ $match->homeTeam->id }}"
                                                name="radio-{{ $match->id }}"
                                                id="radio-{{ $match->id }}"
                                                type="radio"
                                                {{ (count(array_filter($predictions)) >= config('settings.max_prediction_per_time') && empty($predictions[$match->id])) ? 'disabled' : '' }}
                                            >
                                            <label for="radio-{{ $match->id }}" style="{{ $match->favorite_team === 'home' ? 'color: red' : '' }}">
                                                {{ $match->homeTeam->name }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        เวลา {{ ThaiDate::toTime($match->match_date) }}
                                        <hr>
                                        {{ $match->rate }}
                                    </td>
                                    <td class="text-right">
                                        <div class="radio-item">
                                            <input
                                                wire:model.live="predictions.{{ $match->id }}"
                                                value="{{ $match->awayTeam->id }}"
                                                name="radio-{{ $match->id }}"
                                                id="radio-{{ $match->id }}-2"
                                                type="radio"
                                                {{ (count(array_filter($predictions)) >= config('settings.max_prediction_per_time') && empty($predictions[$match->id])) ? 'disabled' : '' }}
                                            >
                                            <label for="radio-{{ $match->id }}-2" style="{{ $match->favorite_team === 'away' ? 'color: red' : '' }}">
                                                {{ $match->awayTeam->name }}
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                @if (session('validate'))
                    <div class="alert alert-danger mt-3">
                        {{ session('validate') }}
                    </div>
                @endif

                @if ($isSelectable)
                    <div class="row mt-4">
                        <div class="col-6 text-end">
                            <button type="reset" wire:click="resetPredictions()" class="btn btn-danger w-75">รีเซ็ต</button>
                        </div>
                        <div class="col-6 text-start">
                            <a class="btn btn-success w-75" wire:click="predicMatch()" role="button">ทายผล</a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

 <!-- กติกา -->
<div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-12 wow fadeInLeft" data-wow-delay="0.1s">
                    <div>
                        <!-- <h1 class="display-4">กติกา ทายผลบอล</h1> -->
                    </div>
                <div class="accordion bg-light rounded p-4" id="accordionExample">
                        <div class="accordion-item border-0 mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-5 fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseTOne">
                                <i class="fa-solid fa-list"></i>  ขั้นตอนการทายผลบอล
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h6>1. เปิดให้ร่วมทายผลบอล เวลา 12.00-23.59น.</h6>
                                    <h6>2. ลูกค้าสามารถเลือกทายผลฟุตบอลได้เพียงวันละ 3 คู่ เท่านั้น</h6>
                                    <h6>3. เมื่อลูกค้าเลือกทายผลฟุตบอลครบทั้ง 3 คู่แล้ว ให้ยืนยันด้วยการคลิกปุ่ม "ทายผล" ด้านล่าง</h6>
                                    <h6>4. หากลูกค้าเลือกทายผลผิดคู่หรือผิดฝั่งสามารถแก้ไขได้ด้วยการคลิกปุ่ม "รีเซ็ต"ด้านล่าง</h6>
                                    <h6>5. เมื่อลูกค้าคลิกปุ่มทายผลเป็นที่เรียบร้อยแล้วจะไม่สามารถกลับมาแก้ไขการทายผลได้อีก</h6>
                                    <h6>6. ทางเว็บไซต์กำหนดเกณฑ์การให้คะแนนในการทายผลแต่ละคู่ดังนี้ ชนะเต็ม 100 เหรียญทอง ชนะครึ่ง 50 เหรียญทอง เสมอหรือแพ้ เท่ากับ 0</h6>
                                    <h6>7. ลูกค้าสามารถติดตามผลการทายผลบอลได้ที่ประวัติคะแนน</h6>
                                    <h6>8. ลูกค้าสามารถตรวจสอบอันดับคะแนนได้ที่ปุ่มอันดับคะแนนด้านบนแล้วเลือกเดือนที่ต้องการตรวจสอบ</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- First modal dialog -->
    <div class="modal fade" id="success-prediction" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="textgreen"> ทายผลสำเร็จ</h4>
                </div>
                <div class="modal-body">
                    <h4 class="p-2">ทายผลสำเร็จ ติดตามผลได้ที่ ประวัติคะแนน</h4>
                </div>
                <div class="modal-footer">
                    <!-- Toogle to second dialog -->
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    <button onclick="window.open('{{ route('point-history') }}','_blank')"
                        class="btn btn-success">ประวัติคะแนน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alert-predicted" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="p-2">วันนี้ท่านได้ร่วมกิจกรรมการทายผลไปแล้ว</h4>
                </div>
                <div class="modal-footer">
                    <!-- Toogle to second dialog -->
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Open first dialog -->
    @push('scripts')
        <script>
          document.addEventListener('livewire:init', () => {
              initializeModals();
          });
          function initializeModals() {
            var modalId = document.getElementById('success-prediction');
            var addSuccessPredictionModal = new bootstrap.Modal(modalId);

                // Listen for the 'show-modal' event to open the modal
            window.addEventListener('open-success-prediction-modal', event => {
              addSuccessPredictionModal.show();
            });

            // Listen for the 'hide-modal' event to close the modal
            window.addEventListener('hide-success-prediction-modal', event => {
              addSuccessPredictionModal.hide();
            });

            // Optional: Handle modal close event to inform Livewire
            modalId.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('closeModal');
            });

            window.addEventListener('open-alert-predicted-modal', event => {
                $('#alert-predicted').modal('show');
            })
          }
        </script>
    @endpush
</div>
