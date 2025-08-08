<div>
    <div class="modal fade show" style="display: block; z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$title}}</h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeModal')"
                            data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <input wire:model="form.name" class="form-control" type="text" placeholder="ชื่อ" {{false === $editable ? 'readonly' : ''}}/>
                    <hr>
                    @foreach($permissions as $groupName => $groupPermissions)
                        <div class="row justify-content-start text-start">
                            <h4>{{ __('website.permissions.'.$groupName) }}</h4>
                            <div class="clearfix">
                                @foreach($groupPermissions as $permission)
                                    <div class="form-check form-check-inline float-start">
                                        <input class="form-check-input border-cyan" type="checkbox"
                                               id="check-permission-{{$permission->id}}"
                                               wire:model="form.selectedPermissions"
                                               value="{{$permission->name}}"
                                        />
                                        <label class="form-check-label"
                                               for="check-permission-{{$permission->id}}">{{__('website.permissions.'.$permission->name)}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        </div>
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="$dispatch('closeModal')">ปิด
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="saveRole()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
</div>
