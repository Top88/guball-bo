<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
        @can('create-team')
        <button type="button" class="btn btn-primary" wire:click="openTeamModal('add')">
            {{ __('website.button.add_team') }}
        </button>
        @endcan

        <!-- Search Input -->
        <div class="col-lg-3 col-sm-12">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    id="teamManagementSearch"
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
                <th class="text-nowrap">{{__('website.general.team_name')}}</th>
                <th class="text-nowrap">{{__('website.general.league_name')}}</th>
                <th class="text-nowrap">{{__('website.general.created_at')}}</th>
            </tr>
        </thead>
        <tbody>
            @if ($teams->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">{{__('website.general.no_data')}}</td>
                </tr>
            @endif
            @foreach ($teams as $team)
                <tr wire:key='{{$team->id}}'>
                    <td>
                        @can('edit-team')
                            <a href="javascript:" class="btn btn-xs btn-primary" wire:click="openTeamModal('edit', {{$team}})"> <i class="fas fa-pen"></i> </a>
                        @endcan
                        @can('delete-team')
                            <a href="javascript:" class="btn btn-xs btn-danger" wire:click="deleteTeam({{$team->id}})"> <i class="fas fa-trash"></i> </a>
                        @endcan
                    </td>
                    {{-- <td class="text-center">
                        @livewire('admin.components.team-status-badge', ['propUser' => $user, 'propLink' => true],  key('user-status-badge-' . $user->id))
                    </td> --}}
                    <td> {{$team->name}} </td>
                    <td> {{$team->league?->name}} </td>
                    <td> {{$team->created_at}} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$teams->links()}}

    @livewire('admin.components.team-modal', [], ['lazy' => true])
</div>
