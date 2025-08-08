@php
use App\Domain\Football\Match\PredictionResult;    
@endphp
<div>
    <!-- ทายผลบอล โชว์ 5 แถว-->
    <div class="container-fluid about py-5">
        <div class="container col-lg-8 col-xs-12">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-4">ประวัติคะแนน</h1>
            </div>
            <select name="pets" id="pet-select" wire:model.live="searchMonth" class="tick-modal-main-select "
                style="color: #636c79 !important; background: #dee2e6  !important; padding: 10px !important;">
                <option value=""> เลือกเดือนที่ต้องการ </option>
                @foreach ($this->monthList ?? [] as $key => $item)
                    <option value="{{ $key }}"> {{ $item }} </option>
                @endforeach
            </select>
 <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>วันที่</th>
                <th>บอลเต็ง <i class="fas fa-star text-warning"></i></th>
                <th>บอลสเต็ป</th>
                <th>เหรียญทอง</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->pointHistories ?? [] as $key => $items)
                @php
                    $singles = $items->where('type', 'single');
                    $steps = $items->where('type', 'step');
                @endphp
                <tr>
                    <td><strong>{{ $key }}</strong></td>

                    {{-- ✅ บอลเต็ง --}}
                    <td>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach ($singles as $prediction)
                                <div class="d-flex align-items-center gap-1">
                                    <i class="fas fa-star text-warning"></i>
                                    <span>{{ $prediction->team->name ?? '-' }}</span>
                                    {!! \App\Domain\Football\Match\PredictionResult::toBadge($prediction->result ?? null) !!}
                                </div>
                            @endforeach
                        </div>
                    </td>

                    {{-- ✅ บอลสเต็ป --}}
                    <td>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach ($steps as $prediction)
                                <div class="d-flex align-items-center gap-1">
                                    <span>{{ $prediction->team->name ?? '-' }}</span>
                                    {!! \App\Domain\Football\Match\PredictionResult::toBadge($prediction->result ?? null) !!}
                                </div>
                            @endforeach
                        </div>
                    </td>

                    {{-- ✅ เหรียญทอง --}}
                    <td>
                        <i class="fas fa-coins text-warning"></i>
                        {{ $items->sum('gain_amount') }}
                    </td>
                </tr>
            @endforeach
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
                <h4 class="textred"><i class="fas fa-coins" style="color: goldenrod"></i> {{ $userGoldCoins }} คะแนน</h4>
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-6">
                <h4>เหรียญเงินทั้งหมด</h4>
            </div>
            <div class="col-6">
                <h4 class="textred"><i class="fas fa-coins" style="color: silver"></i> {{ $userSilverCoins }} เหรียญ</h4>
            </div>
        </div>

         <div class="row text-center">
            <div class="col-4"></div>
            <div class="col-4 mt-3">
                <button type="button" class="btn btn-lg btn-success" data-bs-toggle="modal" href="#modal"
                    role="button"><i class="fas fa-coins"></i> แลกเครดิต</button>
            </div>
            <div class="col-4"></div>
        </div>

        <div class="row text-center p-3">
            <h5 class="textred">
                ** {{config('settings.exchange_credit_cost', 2000)}} เหรียญทอง สามารถแลกได้ {{config('settings.exchange_credit_gain_amount', 0)}} เครดิต
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
                        <p>ใช้ {{config('settings.exchange_credit_cost')}} เหรียญทอง แลกเครดิต</p>
                    </div>
                    <div class="modal-footer">
                        <!-- Toogle to second dialog -->
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        <button wire:click="submitExchangeCredit()" class="btn btn-success"><i
                                class="fas fa-coins"></i>ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
