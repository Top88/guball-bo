
<div>
    <!-- Teleport the Modal to the end of the body -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="team-modal" tabindex="-1" aria-labelledby="teamModalLabel">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <form wire:submit.prevent="submit">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="teamModalLabel">{{ $modalTypeLabel }}  {{__('website.general.team')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label for="team_name" class="form-label">{{__('website.general.team_name')}}</label>
                                <input type="text" wire:model="teamName" class="form-control" id="team_name">
                                @error('teamName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="league_name" class="form-label">{{__('website.general.league_name')}}</label>
                                @livewire('admin.components.league-select', ['fieldName' => 'league', 'modalId' => 'team-modal'])
                            </div>
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
<script>
    document.addEventListener('livewire:init', () => {
        initializeModals();
    });
    function initializeModals()
    {
         // Initialize Bootstrap modal
        var modalId = document.getElementById('team-modal');
        var addTeamModal = new bootstrap.Modal(modalId);

        // Listen for the 'show-modal' event to open the modal
        window.addEventListener('open-team-modal', event => {
            addTeamModal.show();
        });

        // Listen for the 'hide-modal' event to close the modal
        window.addEventListener('hide-team-modal', event => {
            addTeamModal.hide();
        });

        // Optional: Handle modal close event to inform Livewire
        modalId.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModal');
        });
    }
</script>
@endpush
