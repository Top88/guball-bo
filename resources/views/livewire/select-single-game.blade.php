@php
    use Carbon\Carbon;
    use App\Helper\ThaiDate;
@endphp

<div wire:init="checkPredicted">
    <div class="container-fluid about py-5">
        <div class="container col-lg-5 col-xs-12">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1 class="display-4">ทายผลบอลเต็ง</h1>
                <h4 class="text-primary">
                    วันที่ {{ ThaiDate::toDateTime(Carbon::now()->toDateTimeString(), isShort: true) }}
                </h4>
            </div>

            <form wire:submit.prevent="predicMatch">
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
                                            <input type="radio"
                                                wire:model.live="predictions.{{ $match->id }}"
                                                value="{{ $match->homeTeam->id }}"
                                                name="radio-{{ $match->id }}"
                                                id="radio-{{ $match->id }}"
                                                {{ count(array_filter($predictions)) >= 1 && empty($predictions[$match->id]) ? 'disabled' : '' }}>
                                            <label for="radio-{{ $match->id }}" style="{{ $match->favorite_team === 'home' ? 'color:red' : '' }}">
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
                                            <input type="radio"
                                                wire:model.live="predictions.{{ $match->id }}"
                                                value="{{ $match->awayTeam->id }}"
                                                name="radio-{{ $match->id }}"
                                                id="radio-{{ $match->id }}-2"
                                                {{ count(array_filter($predictions)) >= 1 && empty($predictions[$match->id]) ? 'disabled' : '' }}>
                                            <label for="radio-{{ $match->id }}-2" style="{{ $match->favorite_team === 'away' ? 'color:red' : '' }}">
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
                            <button type="button" wire:click="resetPredictions" class="btn btn-danger w-75">รีเซ็ต</button>
                        </div>
                        <div class="col-6 text-start">
                            <button type="submit" class="btn btn-success w-75">ทายผล</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="success-prediction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="textgreen">ทายผลสำเร็จ</h4>
                </div>
                <div class="modal-body">
                    <h4 class="p-2">ทายผลสำเร็จ ติดตามผลได้ที่ประวัติคะแนน</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    <a href="{{ route('point-history') }}" target="_blank" class="btn btn-success">ประวัติคะแนน</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Already Predicted Modal -->
    <div class="modal fade" id="alert-predicted-single" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="p-2">วันนี้ท่านได้ร่วมกิจกรรมการทายผลไปแล้ว</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const successModalEl = document.getElementById('success-prediction');
                const alertModalEl = document.getElementById('alert-predicted-single');

                const successModal = new bootstrap.Modal(successModalEl);
                const alertModal = new bootstrap.Modal(alertModalEl);

                window.addEventListener('open-success-prediction-modal', () => {
                    successModal.show();
                });

                window.addEventListener('hide-success-prediction-modal', () => {
                    successModal.hide();
                });

                successModalEl.addEventListener('hidden.bs.modal', function () {
                    Livewire.dispatch('closeModal');
                });

                window.addEventListener('open-alert-predicted-single-modal', () => {
                    alertModal.show();
                });
            });
        </script>
    @endpush
</div>
