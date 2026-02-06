<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<link rel="stylesheet" href="{{ asset('theme/style.css') }}">
	<link rel="stylesheet" href="{{ asset('theme/css/colors.php?color=fe6400') }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
	<!-- DataTable CSS -->
    <link rel="stylesheet" href="{{ asset('theme/include/DataTables/datatables.css') }}">


	@yield('pagecss')
	
		<title>{{ $page->name }}</title>
	
	<style>
		table {
			font-size:12px;
		}

        .dt-buttons .processing::after{
            display: none;
        }

		@media print {
			table {
				width: 100%; /* Ensure the table uses full width */
			}
			th, td {
				font-size: 12px; /* Adjust font size for printing */
			}
			/* Hide any elements not needed in print */
			.no-print {
				display: none;
			}
		}
	</style>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper">
		

		<!-- Content
		============================================= -->
		<section id="content">
			@yield('content')
		</section><!-- #content end -->

	

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="uil uil-angle-up"></div>






    <!-- Bootstrap Data Table Plugin -->
    <script src="{{ asset('theme/js/jquery-3.4.1.min.js') }}" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/include/DataTables/datatables.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/datatables/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/dashforge.js') }}"></script>

    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script> --}}

	
	<script>
		$(document).ready(function() {

			// Disable DataTables alerts
			$.fn.dataTable.ext.errMode = 'none';  // Suppress the error alert

			var table = new DataTable('#report', {
				responsive: true,
				layout: {
					topStart: {
						buttons: [
							{
								extend: 'copy',
								action: function(e, dt, button, config) {
									logExportActivity('copy');  // Custom audit log function
									$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
								}
							},
							{
								extend: 'excel',
								action: function(e, dt, button, config) {
									logExportActivity('excel');  // Custom audit log function
									$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
								}
							},
							{
								extend: 'pdf',
								action: function(e, dt, button, config) {
									logExportActivity('pdf');  // Custom audit log function
									$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
								},
								orientation: 'landscape', 
								pageSize: 'A2',
								title: '{{ $page->name }} | Foreign Service Institute',
								exportOptions: {
									columns: ':visible'
								}
							},
							{
								extend: 'csv',
								action: function(e, dt, button, config) {
									logExportActivity('csv');  // Custom audit log function
									$.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
								}
							},
							{
								extend: 'print',
								action: function(e, dt, button, config) {
									logExportActivity('print');  // Custom audit log function
									$.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
								}
							},
							{
								extend: 'colvis'
							}
						]
					}
				},

				columnDefs: [
					{
						visible: false,
						target: target_cols
					}
				]
			});

			// You can add custom error handling here, if desired
			table.on('error.dt', function(e, settings, techNote, message) {
				console.log('DataTables error: ', message);  // Log the error if needed
			});
		});


		function logExportActivity(type) {
			$.ajax({
				url: '{{ route("reports.log-export-activity") }}',
				type: 'POST',
				data: {
					_token: $('meta[name="csrf-token"]').attr('content'),  // Add CSRF token for Laravel
					action: type,
					description: 'User exported the {{ $page->name }} report as ' + type
				},
				success: function(response) {
					console.log('Audit logged successfully.');
				},
				error: function(xhr) {
					console.log('Error logging audit.');
				}
			});
		}
    </script>
   

	@yield('pagejs')

</body>
</html>