@extends('theme.main')

@section('pagecss')
<!-- Add any specific CSS for the show page here -->
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">
            <div class="col-md-6">
                <strong class="text-uppercase">Requisition Details</strong>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('issuance.requisitions.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Requisition Details</span>
                        <div class="card-tools">
                            <a href="javascript:void(0)" class="text-decoration-none" onclick="print_area('print-area')">
                                <i class="fa fa-print"></i> Print
                            </a>

                            @if(RolePermission::has_permission(4,auth()->user()->role_id,3) && ($requisition->status != 'CANCELLED' && $requisition->status != 'POSTED'))
                                <span style="margin: 7px;"> | </span>
                                <a href="javascript:void(0)" class="text-decoration-none" onclick="single_post({{ $requisition->id }})" title="Post Transaction">
                                    <i class="bi-send"></i> Post Transaction
                                </a>
                            @endif

                            @if(RolePermission::has_permission(4,auth()->user()->role_id,2) && ($requisition->status != 'CANCELLED' && $requisition->status != 'POSTED'))
                                <span style="margin: 7px;"> | </span>
                                <a href="javascript:void(0)" class="text-decoration-none" onclick="single_cancel({{ $requisition->id }})" title="Cancel Transaction">
                                    <i class="fa fa-cancel"></i> Cancel Transaction
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="print-area">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-plaintext">
                                        <strong><small class="rounded text-white {{ $requisition->status == 'SAVED' ? 'bg-warning' : ($requisition->status == 'CANCELLED' ? 'bg-danger' : 'bg-success') }} p-1">{{ $requisition->status }}</small></strong>
                                        <small class="text-secondary" {{ $requisition->status == 'SAVED' ? 'hidden' : '' }}> | 
                                            @if($requisition->status == 'POSTED')
                                                {{ User::getName($requisition->posted_by) }} ({{ $requisition->posted_at }})
                                            @else
                                                {{ User::getName($requisition->cancelled_by) }} ({{ $requisition->cancelled_at }})
                                            @endif
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">Reference #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $requisition->ref_no }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date Requested</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $requisition->date_requested }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Requested by</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ User::getName($requisition->requested_by) }} <small class="text-secondary">({{ $requisition->requested_at }})</small></p>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date Needed</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $requisition->date_needed }}</p>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Type</label>
                                <div class="col-sm-9">
                                    @php
                                        $types = json_decode($requisition->requisition_type, true) ?? [];
                                    @endphp

                                    @if(count($types))
                                        @foreach($types as $type)
                                            <input name="requisition_type[]" disabled type="checkbox" value="{{ $type }}" checked>
                                            <label class="mx-2">{{ $type }}</label>
                                        @endforeach
                                    @else
                                        -
                                    @endif

                                    {{-- @foreach(json_decode(env('REQUISITION_TYPE'), true) as $type)
                                        @if(in_array($type, json_decode($requisition->requisition_type, true) ?? []))
                                            <input disabled name="requisition_type[]" type="checkbox" value="{{ $type }}" {{ in_array($type, json_decode($requisition->requisition_type, true) ?? []) ? 'checked' : '' }}><label class="mx-2">{{ $type }}</label>
                                        @endif
                                    @endforeach --}}
                                </div>
                            </div>
                            <div class="form-group row" id="vehicle-row" @if(!$requisition->vehicle_id) style="display:none;" @endif>
                                <label for="vehicle_id" class="col-sm-3 col-form-label">Vehicle</label>
                                <div class="col-sm-9">
									@php
										$selectedVehicles = old(
											'vehicle_id',
											is_array($requisition->vehicle_id ?? null)
												? $requisition->vehicle_id
												: json_decode($requisition->vehicle_id ?? '[]', true)
										) ?? [];
									@endphp

									<select id="vehicle_id" name="vehicle_id[]" class="select-tags form-select" multiple disabled style="width:100%;">
										<option value="">-- SELECT VEHICLE --</option>

										@foreach($vehicles as $vehicle)
											<option value="{{ $vehicle->id }}"
												data-type="{{ $vehicle->type }}"
												{{ in_array($vehicle->id, $selectedVehicles) ? 'selected' : '' }}>
												{{ $vehicle->plate_no . ' - ' . ($vehicle->type ?? 'NO TYPE') }}
											</option>
										@endforeach
									</select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Parts Needed</label>
                                <div class="col-sm-9">
                                    @php
                                        $partsNeeded = json_decode($requisition->requisition_parts_needed, true) ?? [];
                                    @endphp

                                    @if(count($partsNeeded))
                                        @foreach($partsNeeded as $part)
                                            <input disabled type="checkbox" value="{{ $part }}" checked>
                                            <label class="mx-2">{{ $part }}</label>
                                        @endforeach
                                    @else
                                        -
                                    @endif

                                    {{-- @foreach(json_decode(env('REQUISITION_PARTS_NEEDED'), true) as $part)
                                        @if(in_array($part, json_decode($requisition->requisition_parts_needed, true) ?? []))
                                            <input disabled name="requisition_parts_needed[]" type="checkbox" value="{{ $part }}" {{ in_array($part, json_decode($requisition->requisition_parts_needed, true) ?? []) ? 'checked' : '' }}><label class="mx-2">{{ $part }}</label>
                                        @endif
                                    @endforeach --}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Assessment</label>
                                <div class="col-sm-9">
                                    @php
                                        $selected = json_decode($requisition->requisition_assessment, true) ?? [];
                                    @endphp

                                    @if(count($selected))
                                        @foreach($selected as $assessment)
                                            <input disabled type="checkbox" value="{{ $assessment }}" checked>
                                            <label class="mx-2">{{ $assessment }}</label>
                                        @endforeach
                                    @else
                                        -
                                    @endif

                                    {{-- @foreach(json_decode(env('REQUISITION_ASSESSMENT'), true) as $assessment)
                                        @if(in_array($assessment, json_decode($requisition->requisition_assessment, true) ?? []))
                                            <input disabled name="requisition_assessment[]" type="checkbox" value="{{ $assessment }}" {{ in_array($assessment, json_decode($requisition->requisition_assessment, true) ?? []) ? 'checked' : '' }}><label class="mx-2">{{ $assessment }}</label>
                                        @endif
                                    @endforeach --}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Purpose</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $requisition->purpose ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $requisition->remarks ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="divider text-uppercase divider-center"><small>Item Details</small></div>

                            <div class="table-responsive-faker">
                                <table class="table table-hover" id="selected_items_table">
                                    <thead>
                                        <tr>
                                            <th width="15%">SKU</th>
                                            <th width="20%">Item</th>
                                            <th width="15%">Unit</th>
                                            <th width="10%">Qty</th>
                                            
                                            @if(App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no))
                                                <th width="10%">Qty Issued</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisition_details as $requisition_detail)
                                            <tr>
                                                <td>
                                                    {{ $requisition_detail->sku }}
                                                    <input name="sku[]" type="text" value="{{ $requisition_detail->sku }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $requisition_detail->item()->withTrashed()->first()->name }}
                                                    <input name="sku[]" type="text" value="{{ $requisition_detail->item()->withTrashed()->first()->name }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $requisition_detail->item->type->name }}
                                                </td>
                                                <td>
                                                    <input name="quantity[]" type="number" step="1" value="{{ $requisition_detail->quantity }}" min="1" class="border-0 bg-transparent" disabled>
                                                </td>

                                                @if(App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no))
                                                    <td>                                                        
                                                        <input name="quantity[]" type="number" step="1" value="{{App\Models\Custom\IssuanceDetail::getIssuedQty($requisition_detail->ref_no, $requisition_detail->item_id)  }}" min="1" class="border-0 bg-transparent" disabled>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="javascript:void(0);" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('issuance.requisitions.index') }}'; }" class="btn btn-secondary mt-4">Back</a>
                        <a @if(!App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no)) hidden @endif href="{{ route('issuance.requisitions.show-issuance',  $requisition->id) }}" class="btn btn-primary mt-4">View Issuance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    @include('theme.layouts.modals')
    
    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="requisitions" name="requisitions">
        <input type="text" id="status" name="status">
    </form>
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

			let selectedVehicleIds = @json(old('vehicle_id', $requisition->vehicle_id ?? []));
			let firstLoad = true;

			function loadVehiclesByType() {

				let selectedTypes = $('input[name="requisition_type[]"]:checked')
					.map(function () { return $(this).val(); })
					.get();

				let $select = $('#vehicle_id');

				if (!selectedTypes.length) {
					$('#vehicle-row').hide();
					$select.empty().trigger('change');
					return;
				}

				$.ajax({
					url: "{{ route('issuance.vehicles.search-vehicle') }}",
					method: "GET",
					data: { types: selectedTypes },
					success: function (response) {

						$select.empty();

						response.forEach(vehicle => {

							let isSelected = firstLoad
								&& selectedVehicleIds.includes(vehicle.id);

							$select.append(new Option(
								`${vehicle.plate_no} - ${vehicle.type ?? 'NO TYPE'}`,
								vehicle.id,
								isSelected,
								isSelected
							));
						});

						$('#vehicle-row').toggle(response.length > 0);

						$select.trigger('change');

						firstLoad = false; // 🔥 VERY IMPORTANT
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
