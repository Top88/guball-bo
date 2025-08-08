<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
        <button type="button" class="btn btn-primary"
                x-data
                x-on:click="$dispatch('openModal', { component: 'admin.components.role-modal', arguments: {{ json_encode(['type' => 'add']) }} })"
        >
            เพิ่มตำแหน่งใหม่
        </button>

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

    <table class="table table-striped table-bordered align-middle">
        <thead>
        <tr class="text-center">
            <th class="text-nowrap"></th>
            <th class="text-nowrap">ตำแหน่ง</th>
            <th class="text-nowrap">สิทธิ์การเข้าถึง</th>
        </tr>
        </thead>
        <tbody>
        @if ($roles->isEmpty())
            <tr>
                <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
            </tr>
        @endif
        @foreach ($roles as $key => $role)
            <tr wire:key='{{$role->id}}'>
                <td>
                    @if($role->name !== 'super-admin')
                        <a href="javascript:" class="btn btn-xs btn-primary"
                           wire:click="$dispatch('openModal', { component: 'admin.components.role-modal', arguments: { type: 'edit', roleId: {{$role->id}} }}, {{$key}})"
                        ><i class="fas fa-edit"></i></a>
                    @endif
                    @if(!in_array($role->name, ['super-admin', 'admin', 'user']))
                        <a href="javascript:" class="btn btn-xs btn-danger" wire:click="deleteRole({{$role->id}})"> <i class="fas fa-trash"></i> </a>
                    @endif
                </td>
                <td class=" text-nowrap">{{$role->name}}</td>
                <td>
                    @if($role->name === 'super-admin')
                        ทั้งหมด
                    @endif
                    @foreach($role->permissions ?? [] as $permission)
                        <span class="badge bg-dark">{{$permission->name}}</span>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{$roles->links()}}


    @livewire('admin.components.league-modal', [], ['lazy' => true])
</div>

