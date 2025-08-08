
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
        @can('create-user')
            <button type="button" class="btn btn-primary" wire:click="openUserModal('add')">
                {{ __('website.button.add_user') }}
            </button>
        @endcan

        <!-- Search Input -->
        <div class="col-lg-3 col-sm-12">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    id="userManagementSearch"
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
                <th class="text-nowrap">สถานะ</th>
                <th class="text-nowrap">สิทธิ์</th>
                <th class="text-nowrap">เบอร์โทรศัพท์</th>
                <th class="text-nowrap">ชื่อ - นามสกุล</th>
                <th class="text-nowrap">ชื่อเล่น</th>
                <th class="text-nowrap">เหรียญเงิน</th>
                <th class="text-nowrap">เหรียญทอง</th>
                <th class="text-nowrap">วันที่สร้าง</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                </tr>

            @endif
            @foreach ($users as $user)
                <tr wire:key='{{$user->id}}'>
                    <td>
                        @if(!auth()->user()->hasRole('super-admin') && $user->hasRole('super-admin'))

                        @else
                            @can('edit-user')
                                <a href="javascript:" class="btn btn-xs btn-primary" wire:click="openUserModal('edit', {{$user}})"> <i class="fas fa-pen"></i> </a>
                            @endcan
                            @can('delete-user')
                                <a href="javascript:" class="btn btn-xs btn-danger" wire:click="deleteUser('{{$user->id}}')"> <i class="fas fa-trash"></i> </a>
                            @endcan
                            @can('change-status-manage-user')
                                <a href="javascript:" class="btn btn-xs btn-dark" wire:click="$dispatch('openModal', { component: 'admin.components.modal.change-silver', arguments: { userId: '{{$user->id}}' }})"><i class="fas fa-coins" style="color: silver"></i></a>
                            @endcan
                        @endif
                    </td>
                    <td class="text-center">
                        @livewire('admin.components.user-status-badge', ['propUser' => $user, 'propLink' => true],  key($user->id))

                    </td>
                    <td>
                        @if(!auth()->user()->hasRole('super-admin') && $user->hasRole('super-admin'))
                            {{$user->getRoleNames()[0] ?? 'ไม่มีตำแหน่ง'}}
                        @else
                            @livewire('admin.components.user-role-select', ['userId' => $user->id, 'role' => $user->getRoleNames()[0] ?? null], key($user->id))
                        @endif
                    </td>
                    <td> {{$user->phone}} </td>
                    <td> {{$user->full_name}} </td>
                    <td> {{$user->nick_name}} </td>
                    <td> <i class="fas fa-coins" style="color:silver"></i> {{number_format($user->coins_silver)}} </td>
                    <td> <i class="fas fa-coins" style="color: goldenrod"></i> {{number_format($user->coins_gold)}} </td>
                    <td> {{$user->created_at}} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$users->links()}}


    @livewire('admin.components.user-modal')
</div>

