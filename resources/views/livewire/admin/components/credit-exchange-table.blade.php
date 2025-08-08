<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
{{--        <button type="button" class="btn btn-primary">--}}
{{--            รายการแลกเครดิต--}}
{{--        </button>--}}

        <!-- Search Input -->
        <div class="col-lg-3 col-sm-12">
            <div class="input-group">
                <input
                        type="text"
                        class="form-control"
                        id="leagueManagementSearch"
                        wire:model="search"
                        wire:keydown.enter.prevent="searchData"
                        placeholder="ค้นหา"
                        aria-label="ค้นหา"
                        aria-describedby="basic-addon2"
                >
                <button
                        class="btn btn-outline-secondary"
                        type="button"
                        wire:click.prevent="searchData"
                >
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <table width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead>
        <tr class="text-center">
            <th class="text-nowrap">สถานะ</th>
            <th class="text-nowrap">รหัสลูกค้า</th>
            <th class="text-nowrap">ชื่อลูกค้า</th>
            <th class="text-nowrap">จำนวนใช้เหรียญ</th>
            <th class="text-nowrap">จำนวนเครดิต</th>
            <th class="text-nowrap">วันที่สร้าง</th>
        </tr>
        </thead>
        <tbody>
        @if ($exchange_list->isEmpty())
            <tr>
                <td colspan="7" class="text-center">{{ __('website.general.no_data') }}</td>
            </tr>
        @endif
            @foreach($exchange_list as $item)
                <tr wire:key='{{$item->id}}'>
                    <td>
                        @livewire('admin.components.credit-exchange-status', [$item->id, $item->exchange_status], key($item->id) )
                    </td>
                    <td>{{$item->user_id}}</td>
                    <td>{{$item->user->nick_name}}</td>
                    <td>{{$item->cost_amount}}</td>
                    <td>{{$item->credit_amount}}</td>
                    <td>{{$item->created_at}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$exchange_list->links()}}

</div>

