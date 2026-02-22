<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app-url" content="{{ getBaseURL() }}">
	<meta name="file-base-url" content="{{ getFileBaseURL() }}">

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon -->
	<link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
	<title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>

	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

	<!-- aiz core css -->
	<link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
	<link rel="stylesheet" href="{{ static_asset('assets/css/aiz-seller.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/seller-custom-style.css') }}">

    <style>
        body {
            font-size: 12px;
            font-family: {!! !empty(get_setting('system_font_family')) ? get_setting('system_font_family') : "'Public Sans', sans-serif" !!}, sans-serif;
        }
    </style>
	<script>
    	var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
	</script>
</head>
<body class="">
	<div class="aiz-main-wrapper">
        @if (Auth::guard('franchise_employee')->check())
            @include('backend.franchise.employees.inc.employee_sidenav')
        @endif
		<div class="aiz-content-wrapper {{ Auth::guard('franchise_employee')->check() ? '' : 'm-0' }}">
            @if (Auth::guard('franchise_employee')->check())
                @include('backend.franchise.employees.inc.employee_nav')
            @endif
			<div class="aiz-main-content">
				<div class="px-15px px-lg-25px">
                    @yield('panel_content')
				</div>
				<div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto border-sm-top">
					<p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ static_asset('assets/js/vendors.js?v=') }}{{ get_setting('current_version') }}" ></script>
	<script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}" ></script>

    @yield('script')

    <script type="text/javascript">
	    @foreach (session('flash_notification', collect())->toArray() as $message)
	        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
	    @endforeach
    </script>
</body>
</html>
