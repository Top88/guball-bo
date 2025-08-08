<div>
    <!-- Teleport the Modal to the end of the body -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="league-modal" tabindex="-1" aria-labelledby="leagueModalLabel">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <form wire:submit.prevent="submit">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leagueModalLabel">{{ $modalTypeLabel }}  {{__('website.general.league')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{__('website.general.name')}}</label>
                                <input type="text" wire:model="name" class="form-control" id="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">{{__('website.general.country')}}</label>
                                <input type="text" wire:model="country" class="form-control" id="country">
                                @error('country') <span class="text-danger">{{ $message }}</span> @enderror
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
<script>
    document.addEventListener('livewire:init', () => {
        initializeModals();
    });
    function initializeModals()
    {
         // Initialize Bootstrap modal
        var modalId = document.getElementById('league-modal');
        var addLeagueModal = new bootstrap.Modal(modalId);

        // Listen for the 'show-modal' event to open the modal
        window.addEventListener('open-league-modal', event => {
            addLeagueModal.show();
        });

        // Listen for the 'hide-modal' event to close the modal
        window.addEventListener('hide-league-modal', event => {
            addLeagueModal.hide();
        });

        // Optional: Handle modal close event to inform Livewire
        modalId.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModal');
        });
    }
</script>
@endpush
