@push('styles')
<link href="{{asset('../assets/admin/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
<style>
.select2-container {
    z-index: 9999; 
}
</style>
@endpush
<div>
    <select class="form-control select-league" wire:model="selectLeagueId" id="select_league_id">
        <option value="">@lang('website.general.non-select', ['attribute' => __('website.general.league')])</option>
        @foreach ($leagues ?? [] as $league)
            <option value="{{$league->id}}"> {{$league->name}} </option>
        @endforeach
    </select>
    @error('selectLeagueId') <span class="text-danger">{{ $message }}</span> @enderror
</div>
@push('scripts')
<script src="{{asset('../assets/admin/plugins/select2/dist/js/select2.min.js')}}"></script>
<script>
    Livewire.on('init-select', (data) => {
        if (data?.[0]?.isModal === true) {
            $('#'+data?.[0]?.modalId).on('shown.bs.modal', function () {
                $(this).find('.select-league').select2({
                    dropdownParent: $('#'+data?.[0]?.modalId),
                    minimumResultsForSearch: 0
                });
            });
        } else {
            $('.select-league').select2();
        }

        $('.select-league').on('change', function (e) {
            let data = $(this).val();
            @this.set('selectLeagueId', data);
        });
    })

    Livewire.on('trigger-select-change', (data) => {
        console.log(data)
        const select = $('.select-league');
        select.val(data?.[0]?.id);
        select.trigger('change');
    })
</script>
@endpush