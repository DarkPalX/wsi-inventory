<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="author" content="SemiColonWeb">
	<meta name="description" content="Get Canvas to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">

	<!-- Font Imports -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">

	<!-- Core Style -->
	<link rel="stylesheet" href="{{ asset('theme/style.css') }}">

	<!-- Font Icons -->
	<link rel="stylesheet" href="{{ asset('theme/css/font-icons.css') }}">
	
	<!-- Plugins/Components CSS -->
	<link rel="stylesheet" href="{{ asset('theme/css/components/select-boxes.css') }}">
	<link rel="stylesheet" href="{{ asset('theme/css/components/bs-select.css') }}">
	<link rel="stylesheet" href="{{ asset('theme/css/components/bs-filestyle.css') }}">
	<link rel="stylesheet" href="{{ asset('theme/css/components/ion.rangeslider.css') }}">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="{{ asset('theme/css/custom.css') }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('theme/css/colors.php?color=C41E3A') }}">

	<!-- DataTable CSS -->
    <link rel="stylesheet" href="{{ asset('theme/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/buttons.dataTables.min.css') }}">

	<style>

		.white-section {
			background-color: #FFF;
			padding: 25px 20px;
			-webkit-box-shadow: 0px 1px 1px 0px #dfdfdf;
			box-shadow: 0px 1px 1px 0px #dfdfdf;
			border-radius: 3px;
		}

		.white-section label { margin-bottom: 30px; }

		.dark .white-section {
			background-color: #333;
			-webkit-box-shadow: 0px 1px 1px 0px #444;
			box-shadow: 0px 1px 1px 0px #444;
		}

	</style>
	
	{{-- FOR PAGINATION HOVER --}}
	<style>
		.col-md-12 a:hover {
			color: #fff;
			text-decoration: underline;
		}
	</style>

	@yield('pagecss')
	
	<!-- Document Title
	============================================= -->

	@if (isset($page->name) && $page->name == 'Home')
		<title>{{ Setting::info()->company_name }}</title>
	@else
		<title>{{ (empty($page->meta_title) ? $page->name : ($page->meta_title == "title" ? $page->name : $page->meta_title) ) }} | {{ Setting::info()->company_name }}</title>
	@endif

	@if(!empty($page->meta_description))
		<meta name="description" content="{{ $page->meta_description }}">
	@endif

	@if(!empty($page->meta_keyword))
		<meta name="keywords" content="{{ $page->meta_keyword }}">
	@endif
	
	<!-- Favicon
	============================================= -->
	<link rel="icon" href="{{ Setting::get_company_favicon_storage_path() }}" type="image/x-icon">
	{{-- <link rel="icon" href="{{ asset('storage/icons/'.Setting::get_company_favicon_storage_path()); }}" type="image/x-icon"> --}}

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper">
		
		<!-- Header
		============================================= -->
		{{-- @if(Auth::user())
			@include('theme.layouts.header')
		@endif --}}
		<!-- #header end -->

		<!-- Content
		============================================= -->
		<section id="content">
			@yield('content')
		</section><!-- #content end -->

		<!-- Alert
		============================================= -->
		@include('theme.layouts.alert')
		<!-- #alert end -->

		<!-- Footer
		============================================= -->
		{{-- @if(Auth::user())
			@include('theme.layouts.footer')
		@endif --}}
		<!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="uil uil-angle-up"></div>

	
	<!-- JavaScripts
	============================================= -->
	<script src="{{ asset('theme/js/plugins.min.js') }}"></script>
	<script src="{{ asset('theme/js/functions.bundle.js') }}"></script>
	
	<!-- SweetAlert2 CDN -->
	<script src="{{ asset('theme/js/sweetalert2@11.js') }}"></script>
	
	<!-- Select-Boxes Plugin -->
	<script src="{{ asset('theme/js/components/select-boxes.js') }}"></script>
	
	<!-- Select Splitter Plugin -->
	<script src="{{ asset('theme/js/components/selectsplitter.js') }}"></script>
	<script src="{{ asset('theme/js/components/bs-select.js') }}"></script>

    <!-- Showing Login Form -->
	<script>
		function showLoginForm() {
			document.getElementById('cover-banner').classList.add('slide-up');
			document.getElementById('login-section').classList.add('show');
		}
	</script>

	<!-- Bootstrap File Upload Plugin -->
	<script src="{{ asset('theme/js/components/bs-filestyle.js') }}"></script>

	<script >
		jQuery(document).ready(function() {
			jQuery(".input-file").fileinput({
				showUpload: false,
				maxFileCount: 10,
				mainClass: "input-group-lg",
				showCaption: true
			});
		});

	</script>

	<!-- Range Slider Plugin -->
	<script src="{{ asset('theme/js/components/rangeslider.min.js') }}"></script>

	<script>
		jQuery(document).ready( function(){
			jQuery(".range_01").ionRangeSlider();
		});
	</script>

    <!-- Bootstrap Data Table Plugin -->
    <script
    src="{{ asset('theme/js/jquery-3.4.1.min.js') }}"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/datatables/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/dashforge.js') }}"></script>

    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>

   

	@yield('pagejs')

</body>
</html>