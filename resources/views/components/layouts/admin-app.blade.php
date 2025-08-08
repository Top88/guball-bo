<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title> {{$title ?? 'GuBall'}} </title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

    @livewireStyles
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('assets/admin/css/vendor.min.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/admin/css/default/app.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/bootstrap/dist/css/bootstrap.min.css')}}">
	<!-- ================== END core-css ================== -->
    @stack('styles')
</head>
<body>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app app-header-fixed app-sidebar-fixed">
		@livewire('admin.layouts.header')
		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar" data-bs-theme="dark">
			@livewire('admin.layouts.sidebar')
		</div>
		<div class="app-sidebar-bg" data-bs-theme="dark"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		<!-- END #sidebar -->

		<!-- BEGIN #content -->
		<div id="content" class="app-content">
			{{ $slot }}
		</div>
		<!-- END #content -->

		<!-- BEGIN scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- END scroll to top btn -->
	</div>
	<!-- END #app -->

    
    @livewireScripts
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('assets/admin/js/vendor.min.js')}}"></script>
	<script src="{{asset('assets/admin/js/app.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- ================== END core-js ================== -->
    @livewire('wire-elements-modal')
	@stack('scripts')

    <script>
        document.addEventListener('livewire:init', function () {
            // Listen for the sweet-alert event
            Livewire.on('sweet-alert', data => {
                Swal.fire({
                    icon: data[0].type,
                    title: data[0].title,
                    showConfirmButton: false,
                    timer: 2000
                });
            });

            Livewire.on('sweet-alert-confirmation', data => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].icon,
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: data[0].confirm_button_text,
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (null !== data[0].confirmed_action) {
                            Livewire.dispatch(data?.[0].confirmed_action, {'data' : data?.[0].data});
                        }
                    }
                });
            });

            // Listen for the redirect-after-delay event
            window.addEventListener('redirect-after-delay', function (event) {
                setTimeout(function () {
                    window.location.href = '/'+event.detail[0].path;
                }, 2000); // 2 seconds delay
            });
        });
    </script>
</body>
</html>
