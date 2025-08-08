
<div>
    <div class="dropdown">
        <button class="btn {{$this->getDropdownColor}} btn-xs dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            {{__('website.user_status.'.$selected)}}
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
            @foreach ($this->otherCases as $case)
                <li><button class="dropdown-item" type="button" wire:click.prevent="changeStatus('{{$case}}')">{{__('website.user_status.'.$case)}}</button></li>
            @endforeach
        </ul>
    </div>
</div>
