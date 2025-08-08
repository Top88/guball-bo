@push('styles')
<link href="{{asset('assets/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" />
<link href="{{asset('../assets/admin/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
<style>
.select2-container {
    z-index: 9999; 
}
</style>
@endpush
<div>
    <!-- Teleport the Modal to the end of the body -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="match-modal" tabindex="-1" aria-labelledby="matchModalLabel">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <form wire:submit.prevent="submit">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="matchModalLabel">{{ $modalTypeLabel }}  {{__('website.general.match')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label for="match_date" class="form-label">{{__('website.general.date')}}</label>
                                <div class="input-group date">
                                    <input type="text" wire:model="matchDate" class="form-control" id="match_date" placeholder="{{__('website.general.select-date')}}">
                                    <span class="input-group-text input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                                @error('matchDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="match_time" class="form-label">{{__('website.general.time')}}</label>
                                <div class="input-group bootstrap-timepicker">
                                    <input id="match_time" type="text" class="form-control" wire:model="matchTime"/>
                                    <span class="input-group-text input-group-addon"><i class="fa fa-clock"></i></span>
                                </div>
                                @error('matchTime') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="league" class="form-label">{{__('website.general.league')}}</label>
                                @livewire('admin.components.league-select', ['fieldName' => 'league', 'modalId' => 'match-modal'])
                                @error('leagueId') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="home_team" class="form-label">{{__('website.general.home_team')}}</label>
                                @livewire('admin.components.team-select', ['fieldName' => 'homeTeam', 'modalId' => 'match-modal'])
                                @error('homeTeam') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="away_team" class="form-label">{{__('website.general.away_team')}}</label>
                                @livewire('admin.components.team-select', ['fieldName' => 'awayTeam', 'modalId' => 'match-modal'])
                                @error('awayTeam') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rate_type" class="form-label">ทีมต่อ</label>
                                <livewire:select-2 :options="$this->favoriteType" onchange="change-favorite" name="selectedFavorite" model="selectedFavorite" :class="'form-control select-dropdown'"/>
                                    @error('selectedFavorite') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rate_type" class="form-label">{{__('website.general.rate')}}</label>
                                <livewire:select-2 :options="$this->rates" onchange="change-rate" name="selectedRate" model="selectedRate" :class="'form-control select-dropdown'"/>
                                    @error('selectedRate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Add more fields as needed -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('website.button.close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('website.button.save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{asset('../assets/admin/plugins/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script>
    document.addEventListener('livewire:init', () => {
        initializeMatchModals();
    });
    function initializeMatchModals()
    {
         // Initialize Bootstrap modal
        const modalId = document.getElementById('match-modal');
        const addMatchModal = new bootstrap.Modal(modalId);
        // Listen for the 'show-modal' event to open the modal
        window.addEventListener('open-match-modal', event => {
            addMatchModal.show();
        });

        // Listen for the 'hide-modal' event to close the modal
        window.addEventListener('hide-match-modal', event => {
            addMatchModal.hide();
        });

        // Optional: Handle modal close event to inform Livewire
        modalId.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModal');
        });
    }

    Livewire.on('init-select', (data) => {
        $('#match-modal').on('shown.bs.modal', function () {
            $(this).find('.select-dropdown').select2({
                dropdownParent: $('#match-modal'),
                minimumResultsForSearch: 0
            });
        });
    })

    const match_date = $("#match_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        format: {
            toDisplay: function (date, format, language) {
                var d = new Date(date);
                const {year, month, day, hours, minutes, seconds} = splitDateTimeFormat(d);
                return `${year}-${month}-${day}`;
            },
            toValue: function (date, format, language) {
                var d = new Date(date);
                const {year, month, day, hours, minutes, seconds} = splitDateTimeFormat(d);
                return `${year}-${month}-${day}`;
            }
        }
    });

    match_date.on('change', function (e) {
        @this.set('matchDate', e.target.value);
    });
    
    const match_time = $("#match_time").timepicker({
        format: 'HH:mm',
        showMeridian: false,
    });

    match_time.on('change', function (e) {
        @this.set('matchTime', e.target.value);
    });

    function splitDateTimeFormat(d)
    {
        return {
            year: d.getFullYear(),
            month: ("0" + (d.getMonth() + 1)).slice(-2),
            day: ("0" + d.getDate()).slice(-2),
            hours: ("0" + d.getHours()).slice(-2),
            minutes: ("0" + d.getMinutes()).slice(-2),
            seconds: ("0" + d.getSeconds()).slice(-2)
        }
    }
</script>
@endpush
