<div>
    <!-- BEGIN page-header -->
    <h1 class="page-header">{{__('website.general.manage_match')}}</h1>
    <!-- END page-header -->
    <!-- BEGIN row -->
    <div class="row">
        <div class="col-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    @livewire('admin.components.match-management-table')
                </div>
                <!-- END panel-body -->
                <!-- BEGIN hljs-wrapper -->
                <div class="hljs-wrapper">
                    <pre><code class="html" data-url="../assets/data/table-manage/responsive.json"></code></pre>
                </div>
                <!-- END hljs-wrapper -->
            </div>
            <!-- END panel -->
        </div>
    </div>
    <!-- END row -->
</div>
