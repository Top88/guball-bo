<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
        @can('create-league')
            <button type="button" class="btn btn-primary" wire:click="openLeagueModal('add')">
                {{ __('website.button.add_league') }}
            </button>
        @endcan

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
                <th class="text-nowrap"></th>
                {{-- <th class="text-nowrap">สถานะ</th> --}}
                <th class="text-nowrap">ชื่อลีค</th>
                <th class="text-nowrap">ประเทศ</th>
                {{-- <th class="text-nowrap">โลโก้</th>
                <th class="text-nowrap">ธง</th>
                <th class="text-nowrap">รายละเอียด</th> --}}
                <th class="text-nowrap">วันที่สร้าง</th>
            </tr>
        </thead>
        <tbody>
            @if ($leagues->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                </tr>
            @endif
            @foreach ($leagues as $league)
                <tr wire:key='{{$league->id}}'>
                    <td>
                        @can('delete-league')
                            <a href="javascript:" class="btn btn-xs btn-danger" wire:click="deleteLeague({{$league->id}})"> <i class="fas fa-trash"></i> </a>
                        @endcan
                    </td>
                    {{-- <td class="text-center">
                        @livewire('admin.components.league-status-badge', ['propUser' => $user, 'propLink' => true],  key('user-status-badge-' . $user->id))
                    </td> --}}
                    <td> {{$league->name}} </td>
                    <td> {{$league->country}} </td>
                    <td> {{$league->created_at}} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$leagues->links()}}


    @livewire('admin.components.league-modal', [], ['lazy' => true])
</div>

