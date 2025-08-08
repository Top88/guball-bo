<div>
    <!-- BEGIN page-header -->
    <h1 class="page-header">{{__('website.general.setting')}}</h1>
    <!-- END page-header -->
    <!-- BEGIN row -->
    <div class="row">
        <div class="col-12">

            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Search Input -->
                        <div class="col-lg-3 col-sm-12">
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="teamManagementSearch"
                                    wire:model="search"
                                    wire:keydown.enter.prevent="searchData"
                                    placeholder="ค้นหา"
                                    aria-label="ค้นหา"
                                    aria-describedby="basic-addon2"
                                >
                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    wire:click.prevent="searchData"
                                >
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered align-middle text-nowrap">
                        <thead>
                            <tr class="text-center">
                                <th class="text-nowrap"></th>
                                <th class="text-nowrap">{{__('website.general.setting_key')}}</th>
                                <th class="text-nowrap">{{__('website.general.setting_value')}}</th>
                                <th class="text-nowrap">{{__('website.general.updated_at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($this->settings->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">{{__('website.general.no_data')}}</td>
                                </tr>
                            @endif
                            @foreach ($this->settings as $key => $setting)
                                <tr wire:key='{{$setting->id}}'>
                                    <td>
                                        <a href="javascript:;" class="btn btn-xs btn-primary"  wire:click="$dispatch('openModal', { component: 'admin.components.modal.edit-setting-modal', arguments: { settingKey: '{{$setting->key}}', value: '{{$setting->value}}', type: '{{$setting->value_type}}' }}, {{$key}})"> <i class="fas fa-pen"></i> </a>
                                    </td>
                                    <td> {{__('website.settings.'.$setting->key)}} </td>
                                    <td>
                                        @if($setting->key === 'show_rank_week_day')
                                            {{$weekDayThai[$setting->value]}}
                                        @else
                                            {{$setting->value}}
                                        @endif
                                    </td>
                                    <td> {{$setting->updated_at}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$this->settings->links()}}
                </div>
                <!-- END panel-body -->
                <!-- BEGIN hljs-wrapper -->
                <div class="hljs-wrapper">
                    <pre><code class="html" data-url="../assets/data/table-manage/responsive.json"></code></pre>
                </div>
                <!-- END hljs-wrapper -->

                <div class="col-12">
                    <button type="button" class="btn btn-danger" wire:click="resetAllSilverCoin()">รีเซตเหรียญทั้งระบบ</button>
                </div>
            </div>
            <!-- END panel -->
        </div>
    </div>
    <!-- END row -->
</div>
