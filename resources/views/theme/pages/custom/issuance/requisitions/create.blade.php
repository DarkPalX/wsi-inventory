@extends('theme.main')

@section('pagecss')
<!-- Plugins/Components CSS -->
<link rel="stylesheet" href="{{ asset('theme/css/components/select-boxes.css') }}">
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">
        
            <div class="col-md-6">
                <strong class="text-uppercase">{{ $page->name }}</strong>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('issuance.requisitions.index') }}">{{ $page->name }}</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Request Details</div>

                        <div class="card-body">
                            
							<form method="post" action="{{ route('issuance.requisitions.store') }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Date Requested</label>
									<div class="col-sm-9">
										<input type="date" class="form-control" id="date_requested" name="date_requested" value="<?= date('Y-m-d'); ?>" min="{{ date('Y-m-d') }}" required>
									</div>
								</div>
								<div class="form-group row" hidden>
									<label for="name" class="col-sm-3 col-form-label">Date Needed</label>
									<div class="col-sm-9">
										<input type="date" class="form-control" id="date_needed" name="date_needed" value="<?= date('Y-m-d'); ?>" min="{{ date('Y-m-d') }}" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Requested By</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" value="{{ Auth::user()->name; }}" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Type</label>
									<div class="col-sm-9">
										@foreach(json_decode(env('REQUISITION_TYPE'), true) as $type)
											<input name="requisition_type[]" type="checkbox" value="{{ $type }}"><label class="mx-2">{{ $type }}</label>
										@endforeach
									</div>
								</div>
								<div class="form-group row" id="vehicle-row" style="display:none;">
									<label for="vehicle_id" class="col-sm-3 col-form-label">Vehicle</label>
									<div class="col-sm-9">
										<select id="vehicle_id" name="vehicle_id[]" class="select-tags form-select" multiple style="width:100%;" data-old='@json(old("vehicle_id", []))'>
											@foreach($vehicles as $vehicle)
												<option value="{{ $vehicle->id }}" data-type="{{ $vehicle->type }}">{{ $vehicle->plate_no . ' - ' . ($vehicle->type ?? 'NO TYPE') }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Parts Needed</label>
									<div class="col-sm-9">
										@foreach(json_decode(env('REQUISITION_PARTS_NEEDED'), true) as $part)
											<input name="requisition_parts_needed[]" type="checkbox" value="{{ $part }}"><label class="mx-2">{{ $part }}</label>
										@endforeach
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Assessment</label>
									<div class="col-sm-9">
										@foreach(json_decode(env('REQUISITION_ASSESSMENT'), true) as $assessment)
											<input name="requisition_assessment[]" type="checkbox" value="{{ $assessment }}"><label class="mx-2">{{ $assessment }}</label>
										@endforeach
									</div>
								</div>

								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Purpose</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="purpose" name="purpose" />
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-3 col-form-label">Remarks</label>
									<div class="col-sm-9">
										<textarea class="form-control" id="remarks" name="remarks"></textarea>
									</div>
								</div>

								<div class="divider text-uppercase divider-center"><small>Item Details</small></div>
								
								<div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover" id="selected_items_table">
											<thead>
												<tr>
													<th width="1%"></th>
													<th width="10%">ID</th>
													<th width="10%">SKU</th>
													<th width="20%">Item</th>
													<th width="10%">Unit</th>
													<th width="10%">Qty</th>
													<th width="15%">Purpose</th>
													<th width="15%">Remarks</th>
												</tr>
											</thead>
											<tbody>
												<!-- Selected items will be appended here -->
											</tbody>
										</table>
									</div>
								</div>

								<div class="divider text-uppercase divider-center"><small>Reference</small></div>

								<div class="form-group row">
									<div class="col-md-11">
										<input type="text" class="form-control" id="item_search" name="item_search" placeholder="Search item via ID or item name .." onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
									</div>
									<div class="col-md-1">
										<button type="button" class="btn btn-secondary" onclick="document.getElementById('item_search').value=''; document.getElementById('item_search').dispatchEvent(new Event('input')); document.getElementById('item_search').dispatchEvent(new Event('change'));">Clear</button>
									</div>
								</div>
								
								<div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover" id="search_results_table">
											<thead>
												<tr>
													<th width="10%">ID</th>
													<th width="15%">SKU</th>
													<th>Item</th>
													<th>Unit</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<!-- Results will be appended here via AJAX -->
											</tbody>
										</table>
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-9">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-light">Cancel</a>
									</div>
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        

    </div>

@endsection

@section('pagejs')
	<script>
		jQuery(document).ready( function(){
			// select Tags
			$(document).ready(function () {
				initSelectTags();
			});

			// jQuery(".select-tags").select2({
			// 	tags: true
			// });
		});

		function initSelectTags(context = document) {
			$(context).find('.select-tags').each(function () {
				if (!$(this).hasClass('select2-hidden-accessible')) {
					$(this).select2({
						tags: true,
						width: '100%'
					});
				}
			});
		}
	</script>

	<script>
		// Handle AJAX search and displaying results
		jQuery(document).ready(function() {
			$ = jQuery;

			$('#item_search').on('input', function() {
				let searchQuery = $(this).val();
				if (searchQuery.length) {
					$.ajax({
						url: '{{ route("issuance.requisitions.search-item") }}',
						method: 'GET',
						data: { query: searchQuery },
						success: function(data) {
							// console.log(data);
							let resultsTableBody = $('#search_results_table tbody');
							resultsTableBody.html(''); // Clear the table

							if (data.results.length) {
								data.results.forEach(item => {
									resultsTableBody.append(`
										<tr>
											<td>${item.id}</td>
											<td>${item.sku}</td>
											<td>${item.name}</td>
											<td>${item.unit}</td>
											<td><button type="button" name="insert_selected[]" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}" data-unit="${item.unit}" data-type="${item.type}" data-inventory="${item.inventory}" data-is_inventory="${item.is_inventory}">Add</button></td>
										</tr>
									`);
								});
							} else {
								resultsTableBody.append('<tr><td class="text-center text-danger" colspan="100$">No items found</td></tr>');
							}
						},
						error: function(xhr) {
							console.log('Error:', xhr.responseText);
						}
					});
				} else {
					$('#search_results_table tbody').html('<tr><td class="text-center text-danger" colspan="100%">Search query is empty</td></tr>');
				}
			});
		});

		
		// Handle adding items to the selected list
		document.addEventListener('click', function(event) {
			let target = event.target.closest('button'); // Get the closest button element
			
			if (!target) return; // Exit if no button is clicked

			let id = target.getAttribute('data-id');
			let sku = target.getAttribute('data-sku');
			let name = target.getAttribute('data-name');
			let unit = target.getAttribute('data-unit');
			let inventory = target.getAttribute('data-inventory');
			let is_inventory = target.getAttribute('data-is_inventory');

			// Handle adding items to the selected list
			if (target.classList.contains('add-item-btn')) {
				let selectedTableBody = document.querySelector('#selected_items_table tbody');
				
				// Check if the item already exists in the selected items table
				let exists = Array.from(selectedTableBody.querySelectorAll('tr')).some(row => {
					return row.querySelector('input[name="item_id[]"]').value === id;
				});

				if (!exists) {
					// Create a new row for the selected items table
					let newRow = selectedTableBody.insertRow(0);

					// Insert cells and their content
					let actionCell = newRow.insertCell(0);
					actionCell.innerHTML = '<button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="'+id+'" data-sku="'+sku+'" data-name="'+name+'" data-unit="'+unit+'"><i class="bi-trash"></i></button>';

					let idCell = newRow.insertCell(1);
					idCell.innerHTML = id + '<input name="item_id[]" type="text" value="' + id +'" hidden>';

					let skuCell = newRow.insertCell(2);
					skuCell.innerHTML = sku + '<input name="sku[]" type="text" value="' + sku +'" hidden>';

					let nameCell = newRow.insertCell(3);
					nameCell.textContent = name;

					let unitCell = newRow.insertCell(4);
					unitCell.textContent = unit;

					let quantityCell = newRow.insertCell(5);
					quantityCell.innerHTML = `
						<input name="quantity[]" 
							type="number" step="1" value="1" min="1" ${is_inventory == 0 ? 'max="7777777"' : `max="${inventory}"`} onclick="this.select()" class="text-end" style="width: 100px;"
							oninput="
								this.value = this.value < 1 ? 1 : Math.min(this.value, this.max); 
								var quantity = parseFloat(this.value); 
							" 
						>
					`;

					let rowIndex = selectedTableBody.rows.length - 1
					let purposeCell = newRow.insertCell(6);
					purposeCell.innerHTML = `
						<select name="item_purpose[${rowIndex}][]" class="select-tags form-select vehicle-child" multiple style="width:100%;"></select>
					`;

					let remarksCell = newRow.insertCell(7);
					remarksCell.innerHTML = '<input name="item_remarks[]" type="text">';
					
					
					initSelectTags(purposeCell);
					syncVehicleToPurpose();


					// Optionally remove the item from the search results
					target.closest('tr').remove();
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Item Already Added',
						text: 'This item is already in the selected list.',
						confirmButtonText: 'OK'
					});
				}
			}

			// Handle removing items from the selected list
			if (target.classList.contains('remove-item-btn')) {
				let searchResultsTableBody = document.querySelector('#search_results_table tbody');

				// Remove the item from the selected items table
				target.closest('tr').remove();
			}
		});

		function checkSelectedItems() {
			const selectedItemsTable = document.querySelector('#selected_items_table tbody');
			if (!selectedItemsTable || selectedItemsTable.children.length === 0) {
				Swal.fire({
					icon: 'warning',
					title: 'No Items Selected',
					text: 'Please select at least one item before saving.',
				});
				return false; // Prevent form submission
			}
			return true; // Allow form submission if items are selected
		}

	</script>

	<script>
		$(document).ready(function() {

			let firstLoad = true; // <-- NEW FLAG

			function loadVehiclesByType() {

				let selectedTypes = $('input[name="requisition_type[]"]:checked')
					.map(function() { return $(this).val(); })
					.get();

				let $select = $('#vehicle_id');
				let oldSelected = $select.data('old') || [];

				if (selectedTypes.length === 0) {
					$('#vehicle-row').hide();
					$select.empty();
					if ($select.hasClass('select2-hidden-accessible')) {
						$select.trigger('change.select2');
					}
					return;
				}

				$.ajax({
					url: "{{ route('issuance.vehicles.search-vehicle') }}",
					method: "GET",
					data: { types: selectedTypes },
					success: function(response) {

						$select.empty();

						response.forEach(function(vehicle) {

							let isSelected = false;

							// USE OLD DATA ONLY on first load
							if (firstLoad) {
								isSelected = oldSelected.includes(vehicle.id.toString());
							}

							$select.append(
								$('<option>', {
									value: vehicle.id,
									text: `${vehicle.plate_no} - ${vehicle.type ?? 'NO TYPE'}`,
									selected: isSelected
								})
							);
						});

						$('#vehicle-row').toggle(response.length > 0);

						if ($select.hasClass('select2-hidden-accessible')) {
							$select.trigger('change.select2');
						}

						// After the VERY FIRST load, stop using oldSelected
						firstLoad = false;
					}
				});
			}

			// Clear selected when checkbox changes
			$('input[name="requisition_type[]"]').on('change', function() {
				let $select = $('#vehicle_id');
				$select.val([]).trigger('change'); // CLEAR SELECTED
				loadVehiclesByType();
			});

			// Page load
			loadVehiclesByType();

		});
	</script>

	<script>
		function syncVehicleToPurpose() {

			const $vehicle = $('#vehicle_id');
			if (!$vehicle.length) return;

			// Vehicles currently selected in the main selector
			const selectedVehicles = $vehicle.find('option:selected').map(function () {
				return {
					value: this.value,
					text: this.text
				};
			}).get();

			$('.vehicle-child').each(function () {

				const $purpose = $(this);

				// Capture existing selections BEFORE touching select2
				const existingValues = $purpose.val() || [];

				// Destroy select2 safely
				if ($purpose.hasClass('select2-hidden-accessible')) {
					$purpose.select2('destroy');
				}

				// Add missing vehicle options ONLY
				selectedVehicles.forEach(v => {
					if ($purpose.find(`option[value="${v.text}"]`).length === 0) {
						$purpose.append(
							$('<option>', {
								value: v.text,
								text: v.text
							})
						);
					}
				});

				// OPTIONAL: remove vehicle options that are no longer selected
				// Uncomment if you want strict syncing
				/*
				$purpose.find('option').each(function () {
					const stillExists = selectedVehicles.some(v => v.value === this.value);
					if (!stillExists && !existingValues.includes(this.value)) {
						$(this).remove();
					}
				});
				*/

				// Restore previous selections
				$purpose.val(existingValues);

				// Re-init select2
				$purpose.select2({
					tags: true,
					width: '100%'
				});

				// Trigger change so Select2 UI updates
				$purpose.trigger('change');
			});
		}
				
		$('#vehicle_id').on('change', function () {
			syncVehicleToPurpose();
		});
	</script>
@endsection