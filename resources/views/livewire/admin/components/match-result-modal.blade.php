<div>
    <!-- Teleport the Modal to the end of the body -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="match-result-modal" tabindex="-1" aria-labelledby="matchResultModalLabel">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <form wire:submit.prevent="submit">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="matchResultModalLabel">{{ $modalTypeLabel }}  {{__('website.general.match_result')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label for="win_team" class="form-label">{{__('website.general.win_team')}}</label>
                                <input type="hidden" wire:model="winTeamSelectedId">
                                <div class="btn-group-horizontal me-5px text-center">
                                    
                                    เจ้าบ้าน
                                    <button type="button" class="btn btn-green {{$winTeamId === $homeTeamId ? 'active' : ''}}" wire:click="winTeamSelected({{$homeTeamId}}, 'home')">{{$homeTeam}} </button>
                                    VS
                                    <button type="button" class="btn btn-green {{$winTeamId === $awayTeamId ? 'active' : ''}}" wire:click="winTeamSelected({{$awayTeamId}}, 'away')">{{$awayTeam}} </button>
                                    เยือน 
                                </div>
                                @error('matchDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn {{$teamMatchResult === 'draw' ? 'btn-success' : 'btn-default' }}" wire:click="draw()">{{__('website.general.draw')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    @if ($teamMatchResult !== 'draw')
                                    <div class="col-6">
                                        <label for="select_win_type" class="form-label">{{__('website.general.win_type')}}</label>
                                        <select class="form-control" wire:model="winType" id="select_win_type">
                                            <option value="">{{__('website.general.please_select')}}</option>
                                            @foreach ($winTypeList as $item)
                                                <option value="{{$item}}">{{__('website.win_type.'.$item)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-6"></div>
                                    @endif
                                    <div class="col-6">
                                        <label for="game_time_minute" class="form-label">{{__('website.general.game_time_minute')}}</label>
                                        <input type="number" class="form-control" wire:model="gameTimeMinute" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="home_team_goal" class="form-label">{{__('website.general.home_team_goal')}}</label>
                                        <input type="number" class="form-control" wire:model="homeTeamGoal" min="0" step="1">
                                    </div>
                                    <div class="col-6">
                                        <label for="away_team_goal" class="form-label">{{__('website.general.away_team_goal')}}</label>
                                        <input type="number" class="form-control" wire:model="awayTeamGoal" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('website.button.close')}}</button>
                            <button type="button" wire:click="submitMatchresult()" class="btn btn-primary">{{__('website.button.save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        initializeMatchResultModals();
    });

    function initializeMatchResultModals()
    {
         // Initialize Bootstrap modal
        var modalId = document.getElementById('match-result-modal');
        var addMatchResultModal = new bootstrap.Modal(modalId);

        // Listen for the 'show-modal' event to open the modal
        window.addEventListener('open-match-result-modal', event => {
            addMatchResultModal.show();
        });

        // Listen for the 'hide-modal' event to close the modal
        window.addEventListener('hide-match-result-modal', event => {
            addMatchResultModal.hide();
        });

        // Optional: Handle modal close event to inform Livewire
        modalId.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModal');
        });
    }
</script>
@endpush
