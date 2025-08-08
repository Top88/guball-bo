@push('styles')
<link href="{{asset('../assets/admin/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
<style>
.select2-container {
    z-index: 9999; 
}
</style>
@endpush
<div>
    <select class="form-control select-rateType" wire:model="selectRateTypeName" id="select_rateType_id">
        <option value="">@lang('website.general.non-select', ['attribute' => __('website.general.rate_type')])</option>
        @foreach ($rateTypes ?? [] as $rateType)
            <option value="{{$rateType}}"> {{__('website.rate_type.'.$rateType)}} </option>
        @endforeach
    </select>
    @error('selectRateTypeName') <span class="text-danger">{{ $message }}</span> @enderror
</div>
@push('scripts')
<script src="{{asset('../assets/admin/plugins/select2/dist/js/select2.min.js')}}"></script>
<script>
    Livewire.on('init-select', (data) => {
        if (data?.[0]?.isModal === true) {
            $('#'+data?.[0]?.modalId).on('shown.bs.modal', function () {
                $(this).find('.select-rateType').select2({
                    dropdownParent: $('#'+data?.[0]?.modalId),
                    minimumResultsForSearch: 0
                });
            });
        } else {
            $('.select-rateType').select2();
        }

        $('.select-rateType').on('change', function (e) {
            let data = $(this).val();
            @this.set('selectRateTypeName', data);
        });
    })

    Livewire.on('trigger-select-change-{{$fieldName}}', (data) => {
        const select = $('.select-rateType');
        select.val(data?.[0]?.name);
        select.trigger('change');
    })
</script>
@endpush