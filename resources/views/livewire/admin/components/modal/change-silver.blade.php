<?php
use App\Domain\UserManagement\ChangeSideEnum;
?>

<div>
    <div class="modal show" style="padding-right: 15px; display: block; z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">แก้ไขเหรียญเงิน <i class="fas fa-coins" style="color: silver"></i> {{$this->user->coins_silver}}</h5>
                </div>
                <div class="modal-body">
                    <!-- inline -->
                    <div class="form-check form-check-inline">
                        <input class="form-check-input is-valid" type="radio" name="changeSide" id="radio1" wire:click="increment()" {{$changeSide === "increment" ? 'checked' : null}} />
                        <label class="form-check-label" for="radio1">เพิ่ม</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input is-invalid" type="radio" name="changeSide" id="radio2" wire:click="decrement()" {{$changeSide === "decrement" ? 'checked' : null}}/>
                        <label class="form-check-label" for="radio2">ลด</label>
                    </div>
                    
                    <input type="number" class="form-control" wire:model="amount">
                 
                    @error('amount')
                        <span style="color: red">{{$message}}</span>
                    @enderror
                    
                </div>
                <div class="modal-footer">
                <!-- Remove if you never want the user to close it manually -->
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')">
                    {{__('website.button.close')}}
                </button>
                <button type="button" class="btn btn-primary" wire:click="save()">
                    {{__('website.button.save')}}
                </button>
                </div>
            </div>
        </div>
    </div>
</div>
