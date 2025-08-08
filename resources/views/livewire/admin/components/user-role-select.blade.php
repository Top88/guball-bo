<div>
    <select wire:model="role" wire:change="changeRole" class="form-control">
        <option value="">ไม่มีตำแหน่ง</option>
        @foreach($allRole as $role)
            @if($role->name === 'super-admin' && !auth()->user()->hasRole('super-admin'))
            @else
                <option value="{{$role->name}}">{{$role->name}}</option>
            @endif

        @endforeach
    </select>
</div>
