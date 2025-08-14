
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Add User Button -->
        @can('create-match')
        <button type="button" class="btn btn-primary" wire:click="openMatchModal('add')">
            {{ __('website.button.add_match') }}
        </button>
        @endcan

        <!-- Search Input -->
        <div class="col-lg-3 col-sm-12">
            <div class="input-group">
                <input type="text" class="form-control" id="matchManagementSearch" wire:model="search"
                    wire:keydown.enter.prevent="searchData" placeholder="ค้นหา" aria-label="ค้นหา"
                    aria-describedby="basic-addon2">
                <button class="btn btn-outline-secondary" type="button" wire:click.prevent="searchData">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <table width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead>
            <tr class="text-center">
                <th class="text-nowrap"></th>
                <th class="text-nowrap">{{ __('website.general.status') }}</th>
                <th class="text-nowrap">{{ __('website.general.match_date') }}</th>
                <th class="text-nowrap">{{ __('website.general.league') }}</th>
                <th class="text-nowrap">{{ __('website.general.home_team') }}</th>
                <th class="text-nowrap">{{ __('website.general.away_team') }}</th>
                <th class="text-nowrap">{{ __('website.general.rate') }}</th>
                <th class="text-nowrap">{{ __('website.general.match_result') }}</th>
                <th class="text-nowrap">{{ __('website.general.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($matches->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">{{ __('website.general.no_data') }}</td>
                </tr>
            @endif
            @foreach ($matches as $match)
                <tr wire:key='{{ $match->id }}'>
                    <td>
                        @if ($match->status === 'active')
                            @can('update-match-result')
                                <a href="javascript:" class="btn btn-xs btn-success"
                                    wire:click="openMatchResultModal('edit', {{ $match }})" title="ผลการแข่งขัน">
                                    <i class="fa-solid fa-trophy"></i>
                                </a>
                            @endcan
                        @endif
                        @if (!in_array($match->status, ['finished']))
                            @can('edit-match')
                                <a href="javascript:" class="btn btn-xs btn-primary"
                                    wire:click="openMatchModal('edit', {{ $match }})" title="แก้ไข">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endcan
                            @can('delete-match')
                                <a href="javascript:" class="btn btn-xs btn-danger"
                                    wire:click="deleteMatch({{ $match->id }})" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </a>
                            @endcan
                        @endif

                    </td>
                    <td> {{ __('website.match_status.' . $match->status) }} </td>
                    <td> {{ substr($match->match_date, 0, -3) }} </td>
                    <td> {{ $match->league?->name }} </td>
                    <td>
                        @if ($match->result?->team_match_result === 'home')
                            <i class="fa-solid fa-trophy trophy-gold"></i>
                        @endif
                        {{ $match->homeTeam?->name }}
                    </td>
                    <td>
                        @if ($match->result?->team_match_result === 'away')
                            <i class="fa-solid fa-trophy trophy-gold"></i>
                        @endif
                        {{ $match->awayTeam?->name }}
                    </td>
                    <td> {{$match->rate}} </td>
                    <td>
                        @if ($match->status === 'finished')
                            @if ($match->result->team_match_result === 'draw')
                                เสมอ
                            @else
                                {{ $match->result->home_team_goal }} - {{ $match->result->away_team_goal }}
                            @endif
                        @endif
                    </td>
                    <td> {{ $match->created_at }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $matches->links() }}

    @livewire('admin.components.match-modal')
    @livewire('admin.components.match-result-modal')
</div>