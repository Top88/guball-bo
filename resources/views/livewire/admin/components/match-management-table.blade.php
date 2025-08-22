<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Add Match --}}
        @can('create-match')
        <button type="button" class="btn btn-primary" wire:click="openMatchModal('add')">
            {{ __('website.button.add_match') }}
        </button>
        @endcan

        {{-- Search --}}
        <div class="col-lg-3 col-sm-12">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    id="matchManagementSearch"
                    wire:model="search"
                    wire:keydown.enter.prevent="searchData"
                    placeholder="ค้นหา"
                    aria-label="ค้นหา"
                    aria-describedby="basic-addon2"
                >
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
                    <td colspan="9" class="text-center">{{ __('website.general.no_data') }}</td>
                </tr>
            @endif

            @foreach ($matches as $match)
                @php
                    $isFinished = ($match->status === 'finished');
                    // ปุ่มถ้วย: ถ้ายังไม่ประกาศผล = add, ถ้าประกาศแล้ว = edit (เพื่อแก้ย้อนหลัง)
                    $resultActionType = $isFinished ? 'edit' : 'add';
                @endphp

                <tr wire:key="match-{{ $match->id }}">
                    <td class="text-nowrap">
                        @can('update-match-result')
                            {{-- ปุ่มสรุป/แก้ผล --}}
                            <a href="javascript:" class="btn btn-xs {{ $isFinished ? 'btn-warning' : 'btn-success' }}"
                               title="{{ $isFinished ? 'แก้ผล' : 'สรุปผล' }}"
                               wire:click="openMatchResultModal(
                                   '{{ $resultActionType }}',
                                   @js([
                                       'id'           => $match->id,
                                       'home_team_id' => $match->home_team_id,
                                       'away_team_id' => $match->away_team_id,
                                   ])
                               )">
                                <i class="fa-solid fa-trophy"></i>
                            </a>
                        @endcan

                        {{-- ปุ่มแก้ไข/ลบ รายการแข่ง (ซ่อนเมื่อ finished) --}}
                        @if (!$isFinished)
                            @can('edit-match')
                                <a href="javascript:" class="btn btn-xs btn-primary"
                                   wire:click="openMatchModal('edit', @js($match->only(['id','league_id','home_team_id','away_team_id','rate','match_date','status'])))"
                                   title="แก้ไข">
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

                    {{-- สถานะ --}}
                    <td>{{ __('website.match_status.' . $match->status) }}</td>

                    {{-- วันที่แข่ง (กัน null/ชนิดข้อมูล) --}}
                    <td>
                        {{ \Carbon\Carbon::parse($match->match_date ?? $match->match_datetime ?? $match->created_at)->format('Y-m-d H:i') }}
                    </td>

                    {{-- ลีก --}}
                    <td>{{ $match->league?->name }}</td>

                    {{-- เจ้าบ้าน (แสดงถ้วยฝั่งชนะ) --}}
                    <td>
                        @if (($match->result?->team_match_result ?? null) === 'home')
                            <span style="color:goldenrod"><i class="fa-solid fa-trophy"></i></span>
                        @endif
                        {{ $match->homeTeam?->name }}
                    </td>

                    {{-- ทีมเยือน (แสดงถ้วยฝั่งชนะ) --}}
                    <td>
                        @if (($match->result?->team_match_result ?? null) === 'away')
                            <span style="color:goldenrod"><i class="fa-solid fa-trophy"></i></span>
                        @endif
                        {{ $match->awayTeam?->name }}
                    </td>

                    {{-- อัตราต่อรอง --}}
                    <td>{{ $match->rate }}</td>

                    {{-- ผลการแข่งขัน (กัน null 100%) --}}
                    <td class="text-center">
                        @php $r = $match->result; @endphp
                        @if ($isFinished)
                            @if ($r && ($r->team_match_result === 'draw'))
                                {{ __('website.general.draw') }}
                            @elseif ($r && $r->home_team_goal !== null && $r->away_team_goal !== null)
                                {{ $r->home_team_goal }} - {{ $r->away_team_goal }}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    {{-- วันที่สร้าง --}}
                    <td>{{ $match->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $matches->links() }}

    @livewire('admin.components.match-modal')
    @livewire('admin.components.match-result-modal')
</div>
