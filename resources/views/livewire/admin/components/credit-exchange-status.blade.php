<div>
    @can('change-status-exchange-credit')
    <div class="dropdown">
        <button class="btn {{$color_status ?? 'btn-default'}} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{__('website.exchange_credit_status.'.$selected)}}
        </button>
        <ul class="dropdown-menu">
            @foreach($status_list as $item)
                <li><a class="dropdown-item" wire:click="changeStatus('{{$item->value}}')">{{__('website.exchange_credit_status.'.$item->value)}}</a></li>
            @endforeach
        </ul>
    </div>
    @endcan
    @cannot('change-status-exchange-credit')
        @livewire('components.exchange-credit-status-badge', ['status' => $selected])
    @endcannot
</div>
