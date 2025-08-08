<div>
    <!-- Teleport the Modal to the end of the body -->
    <div wire:teleport="body">
        <div wire:ignore.self class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="userModalLabel">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <form wire:submit.prevent="submit">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userModalLabel">{{ $modalTypeLabel }}  {{__('website.general.user')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{__('website.general.phone')}}</label>
                                <input type="text" wire:model="phone" class="form-control" id="phone" autocomplete='0812345678' >
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{__('website.general.password')}}</label>
                                <input type="text" wire:model="password" class="form-control" id="password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">{{__('website.general.full_name')}}</label>
                                <input type="text" wire:model="fullName" class="form-control" id="full_name">
                                @error('fullName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nick_name" class="form-label">{{__('website.general.nick_name')}}</label>
                                <input type="text" wire:model="nickName" class="form-control" id="nick_name">
                                @error('nickName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="coins_silver" class="form-label">{{__('website.general.coins_silver')}}</label>
                                <input type="text" wire:model="coinsSilver" class="form-control" id="coins_silver" step="0.01">
                                @error('coinsSilver') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="coins_gold" class="form-label">{{__('website.general.coins_gold')}}</label>
                                <input type="text" wire:model="coinsGold" class="form-control" id="coins_gold" step="0.01">
                                @error('coinsGold') <span class="text-danger">{{ $message }}</span> @enderror
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

        const silver = document.getElementById('coins_silver');
        this.setNumberFormat(silver)
        const gold = document.getElementById('coins_gold');
        this.setNumberFormat(gold)
        
    });
    function initializeModals()
    {
         // Initialize Bootstrap modal
        var modalId = document.getElementById('user-modal');
        var addUserModal = new bootstrap.Modal(modalId);

        // Listen for the 'show-modal' event to open the modal
        window.addEventListener('open-user-modal', event => {
            addUserModal.show();
        });

        // Listen for the 'hide-modal' event to close the modal
        window.addEventListener('hide-user-modal', event => {
            addUserModal.hide();
        });

        // Optional: Handle modal close event to inform Livewire
        modalId.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModal');
        });
    }

    function setNumberFormat(input)
    {
        input.addEventListener('blur', function () {
            let value = input.value;

            // Remove all non-digit characters (e.g., commas, currency symbols)
            value = value.replace(/[^\d.-]/g, '');

            // Convert to float
            let floatVal = parseFloat(value);
            if (isNaN(floatVal)) {
                floatVal = 0;
            }

            // Format as currency (e.g., USD)
            input.value = floatVal.toLocaleString('en-US');
        });
    }
</script>
@endpush
