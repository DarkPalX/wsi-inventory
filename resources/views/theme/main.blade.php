<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="author" content="SemiColonWeb">
	<meta name="description" content="Get Canvas to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">

	<!-- Font Imports -->
<!-- 	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
	<!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet"> -->

	

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
	<link rel="stylesheet" href="{{ asset('theme/css/colors.php?color=fe6400') }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
	<!-- DataTable CSS -->
    <link rel="stylesheet" href="{{ asset('theme/include/DataTables/datatables.css') }}">

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
		body {
			font-family: Poppins;
			font-size:14px;
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
		@if(Auth::user())
			@include('theme.layouts.header')
		@endif
		<!-- #header end -->

		<!-- Slider
		============================================= -->
		{{-- @if(Auth::user())
			@include('theme.layouts.banner')
		@endif --}}
		<!-- #slider end -->

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
	
    <!-- Bootstrap Select Plugin -->
	<script src="{{ asset('theme/js/components/select2.min.js') }}" defer></script>
	
    <!-- Bootstrap Data Table Plugin -->
    <script src="{{ asset('theme/js/jquery-3.4.1.min.js') }}" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/include/DataTables/datatables.js') }}"></script>

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
            
			// Disable DataTables alerts
			$.fn.dataTable.ext.errMode = 'none';  // Suppress the error alert

			jQuery(".range_01").ionRangeSlider();
			
		});
	</script>

	<!-- for checkboxes on index tables -->
	<script>
		function updateDropdownState() {
			var checkboxes = document.querySelectorAll('.select-item:checked');
			var actionDropdownBtn = document.getElementById('action_dropdown_btn');
			var actionDropdown = document.getElementById('action_dropdown');
			var deleteOption = document.querySelector('.dropdown-item[data-action="delete"]');
			var restoreOption = document.querySelector('.dropdown-item[data-action="restore"]');
			var invalidOption = document.querySelector('.dropdown-item[data-action="invalid"]');
		
			var hasTrashed = Array.from(checkboxes).some(checkbox => checkbox.classList.contains('item-trashed'));
			var allTrashed = Array.from(checkboxes).every(checkbox => checkbox.classList.contains('item-trashed'));
			var noneTrashed = Array.from(checkboxes).every(checkbox => !checkbox.classList.contains('item-trashed'));
		
			// Enable or disable the dropdown button
			actionDropdownBtn.disabled = checkboxes.length === 0;
			actionDropdown.hidden  = checkboxes.length === 0;
		
			// Show/Hide options based on the selection state
			if (noneTrashed) {
				deleteOption.style.display = 'block';
				restoreOption.style.display = 'none';
				invalidOption.style.display = 'none';
			} else if (allTrashed) {
				deleteOption.style.display = 'none';
				restoreOption.style.display = 'block';
				invalidOption.style.display = 'none';
			} else {
				deleteOption.style.display = 'none';
				restoreOption.style.display = 'none';
				invalidOption.style.display = 'block';
			}
		}
		
		// Update dropdown state when the "select-all" checkbox is changed
		document.getElementById('select-all').addEventListener('change', function() {
			var checkboxes = document.querySelectorAll('.select-item');
			
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = document.getElementById('select-all').checked;
			});
		
			updateDropdownState();
		});
		
		// Update dropdown state when individual checkboxes are changed
		document.querySelectorAll('.select-item').forEach(function(checkbox) {
			checkbox.addEventListener('change', updateDropdownState);
		});
	</script>

	<!-- for read more toggle -->
	<script>
		function toggleText(id) {
			var text = document.getElementById('remarks-text-' + id);
			var readMore = document.getElementById('read-more-' + id);

			if (text.style.webkitLineClamp === '2') {
				// Expand to full text
				text.style.webkitLineClamp = 'unset';
				readMore.textContent = ' See Less ..';
			} else {
				// Collapse to 2 lines
				text.style.webkitLineClamp = '2';
				readMore.textContent = ' See More ..';
			}
		}

		// Function to check if text is overflowing and show/hide the "Read More" button accordingly
		function checkOverflow(id) {
			var text = document.getElementById('remarks-text-' + id);
			var readMore = document.getElementById('read-more-' + id);

			// Check if the content is overflowing
			if (text.scrollHeight > text.clientHeight) {
				readMore.style.display = 'inline'; // Show "Read More" if text overflows
			} else {
				readMore.style.display = 'none'; // Hide "Read More" if no overflow
			}
		}

		// Check for overflow on page load and when resizing the window
		window.onload = function() {
			document.querySelectorAll('.remarks-text').forEach(function(element) {
				var id = element.getAttribute('id').split('-').pop();
				checkOverflow(id);
			});
		};
		window.onresize = function() {
			document.querySelectorAll('.remarks-text').forEach(function(element) {
				var id = element.getAttribute('id').split('-').pop();
				checkOverflow(id);
			});
		};
	</script>

	<script>
		$(document).on('keydown', 'input[type=text], input[type=number], textarea', function (e) {
			if (e.key === 'Tab') {
				e.preventDefault(); // stop default jump

				let type = $(this).attr('type');   // text or number
				let list = $(`input[type=${type}], textarea`)
					.filter(':visible:not([disabled]):not([readonly])');

				let index = list.index(this);

				if (!e.shiftKey) {
					// TAB forward
					let next = list[index + 1] || list[0]; // cycle
					next.focus();
				} else {
					// SHIFT + TAB backward
					let prev = list[index - 1] || list[list.length - 1]; // cycle
					prev.focus();
				}
			}
		});
	</script>

	@yield('pagejs')

</body>
</html>