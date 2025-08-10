@php
    use App\Domain\Football\Match\PredictionResult;
@endphp
<div>
    <!-- ทายผลบอล โชว์ 5 แถว-->
    <div class="container-fluid about py-5">
        <div class="container col-lg-8 col-xs-12">
            <div class="text-center mx-auto" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1>ประวัติการแลกเครดิต</h1>
            </div>
{{--            <select name="pets" id="pet-select" wire:model.live="searchMonth" class="tick-modal-main-select "--}}
{{--                    style="color: #636c79 !important; background: #dee2e6  !important; padding: 10px !important;">--}}
{{--                <option value=""> เลือกเดือนที่ต้องการ </option>--}}
{{--                @foreach ($this->monthList ?? [] as $key => $item)--}}
{{--                    <option value="{{ $key }}"> {{ $item }} </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
            <table>
                <thead>
                <tr>
                    <th>วันที่</th>
                    <th>จำนวนเหรียญที่ใช้แลก</th>
                    <th>จำนวนเครดิต</th>
                    <th>สภานะ</th>
                </tr>
                </thead>

                <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{$history->created_at}}</td>
                        <td>{{$history->cost_amount}}</td>
                        <td>{{$history->credit_amount}}</td>
                        <td> @livewire('components.exchange-credit-status-badge', [$history->exchange_status]) </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
