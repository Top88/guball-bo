@push('styles')
<link href="{{asset('../assets/admin/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
<style>
.select2-container {
    z-index: 9999; 
}
</style>
@endpush
<div>
    <select class="form-control" wire:model="{{$fieldName}}" id="select_team_id_{{$fieldName}}">
        <option value="">@lang('website.general.non-select', ['attribute' => __('website.general.team')])</option>
        @foreach ($teams ?? [] as $team)
            <option value="{{$team->id}}"> {{$team->name}} </option>
        @endforeach
    </select>
    @error($fieldName) <span class="text-danger">{{ $message }}</span> @enderror
</div>
@push('scripts')
<script src="{{asset('../assets/admin/plugins/select2/dist/js/select2.min.js')}}"></script>
<script>
    Livewire.on('init-select-team-{{$fieldName}}', (data) => {
        if (data?.[0]?.isModal === true) {
            $('#'+data?.[0]?.modalId).on('shown.bs.modal', function () {
                $(this).find('#select_team_id_'+data?.[0].fieldName).select2({
                    dropdownParent: $('#'+data?.[0]?.modalId),
                    minimumResultsForSearch: 0
                });
            });
        } else {
            $('select_team_id_'+data?.[0].fieldName).select2();
        }

        $('#select_team_id_'+data?.[0].fieldName).on('change', function (e) {
            let val = $(this).val();
            @this.set('selectTeamId', val);
        });
    })

    Livewire.on('trigger-select-change-team-{{$fieldName}}', (data) => {
        const select = $('#select_team_id_'+data?.[0].field_name);
        select.val(data?.[0].id);
        select.trigger('change');
    })
</script>
@endpush