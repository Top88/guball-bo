
<div>
    <div
    class="modal show"
    style="padding-right: 15px; display: block; z-index: 9999;"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{__('website.settings.'.$settingKey)}}</h5>
          <!-- (Optional) Remove this to prevent any manual closing at all:
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body">
            @if($settingKey === 'show_rank_week_day')
                <select wire:model="settingValue" class="form-control">
                    @foreach($weekDayThai as $key => $day)
                        <option value="{{$key}}">
                            {{$day}}
                        </option>
                    @endforeach

                </select>
            @else
                <input type="text" class="form-control" wire:model="settingValue">
            @endif
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
