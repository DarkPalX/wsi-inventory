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
                        <li class="breadcrumb-item"><a href="{{ route('issuance.transactions.index') }}">{{ $page->name }}</a></li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Transaction Details</div>

                        <div class="card-body">
                            
							<form method="post" action="{{ route('issuance.transactions.update', $transaction->id) }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								@method('put')

								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">RIS #</label>
									<div class="col-sm-10">
										<input type="text" id="ris_number" class="form-control" value="{{ $transaction->ris_no }}" autocomplete="off" placeholder="Type to search RIS #" list="ris_number_list" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }" disabled>
										<input type="text" name="ris_no" value="{{ $transaction->ris_no }}" hidden>

										<datalist id="ris_number_list"></datalist>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Technical Report #</label>
									<div class="col-sm-10">
										<input type="text" id="technical_report_no" name="technical_report_no" class="form-control" value="{{ $transaction->technical_report_no }}"  autocomplete="off" placeholder="Enter Technical Report Control #" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
									</div>
								</div>
								<div class="form-group row" hidden>
									<label class="col-sm-2 col-form-label">Receiving Agency</label>
									<div class="col-sm-10">
										<select id="receiver_id" name="receiver_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;">
											<option value="">-- SELECT RECEIVERS --</option>
											@foreach($receivers as $receiver)
												<option value="{{ $receiver->id }}" {{ in_array($receiver->id, json_decode($transaction->receiver_id ?? '[]', true)) ? 'selected' : '' }}>{{ $receiver->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Receivers</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="actual_receiver" name="actual_receiver" value="{{ $transaction->actual_receiver }}">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Truck Plate #</label>
									<div class="col-sm-10">
										<select class="vehicle_id select-tags form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" multiple style="width:100%;" required disabled>
											@foreach($vehicles as $vehicle)
												<option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, array_map('intval', (array) (json_decode($transaction->vehicle_id, true) ?? $transaction->vehicle_id))) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
												{{-- <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, json_decode($transaction->vehicle_id ?? '[]', true)) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option> --}}
											@endforeach
										</select>
										<select id="vehicle_id" name="vehicle_id[]" class="vehicle_id form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" multiple aria-hidden="true" style="width:100%;" required hidden>
											@foreach($vehicles as $vehicle)
												<option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, array_map('intval', (array) (json_decode($transaction->vehicle_id, true) ?? $transaction->vehicle_id))) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
												{{-- <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, json_decode($transaction->vehicle_id ?? '[]', true)) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option> --}}
											@endforeach
										</select>
										@error('vehicle_id')
											<small class="text-danger">The truck plate # is required</small>
										@enderror
									</div>
								</div>
								{{-- <div class="form-group row">
									<label class="col-sm-2 col-form-label">Truck Plate #</label>
									<div class="col-sm-10">
										<select id="vehicle_id" name="vehicle_id" class="select-tags form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" aria-hidden="true" style="width:100%;" required>
											<option value="">-- SELECT PLATE NO. --</option>
											@foreach($vehicles as $vehicle)
												<option value="{{ $vehicle->id }}" {{ old('vehicle_id', $transaction->vehicle_id) == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
											@endforeach
										</select>
										@error('vehicle_id')
											<small class="text-danger">The truck plate # is required</small>
										@enderror
									</div>
								</div> --}}
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Release Date</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_released" name="date_released" value="{{ $transaction->date_released }}" required>
									</div>
								</div>
								{{-- <div id="attachments_input" class="form-group row" @if(!is_null($transaction->attachments)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Attachments</label>
									<div class="col-sm-10">
										<input id="attachments" name="attachments[]" class="input-file" type="file" data-show-upload="false" data-show-caption="true" data-show-preview="false" multiple>
									</div>
								</div>
								<div id="attachments_display" class="form-group row" @if(is_null($transaction->attachments)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Attachments</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="bi-file-earmark"></i>
												</span>
											</div>
											<input type="text" value="{{ implode(', ', array_map('basename', json_decode($transaction->attachments ?? '[]', true))) }}" class="form-control" readonly>
											<div class="input-group-append">
												<button type="button" class="btn btn-outline-danger" onclick="remove_file('#attachments_display', '#attachments_input')">
													<i class="bi-trash"></i>
												</button>
											</div>
										</div>
									</div>
								</div> --}}
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Remarks</label>
									<div class="col-sm-10">
										<textarea class="form-control" id="remarks" name="remarks">{{ $transaction->remarks }}</textarea>
									</div>
								</div>
								<div class="form-group row" hidden>
									<div class="col-sm-2">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="is_for_sale" name="is_for_sale" onchange="togglePriceInfo(this)" {{ $transaction->is_for_sale ? 'checked' : '' }}>
											<label class="form-check-label" for="is_for_sale"><strong>For Sale</strong></label>
										</div>
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
													<th width="15%">SKU</th>
													<th width="20%">Item</th>
													<th width="10%">Unit</th>
													<th width="15%" class="text-end">Cost</th>
													<th width="10%" class="text-end">Qty</th>
													<th width="10%" class="text-end">Subtotal</th>
													<th width="5%"></th>
												</tr>
											</thead>
											
											<tbody id="selected_items_rows">
												@foreach($issuance_details as $issuance_detail)
													<tr>
														<td>
															{{-- <button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="{{ $issuance_detail->item_id }}" data-sku="{{ $issuance_detail->sku }}" data-name="{{ $issuance_detail->item()->withTrashed()->first()->name }}" data-price="{{ $issuance_detail->item()->withTrashed()->first()->price ?? 0.00 }}"><i class="bi-trash"></i></button> --}}
														</td>
														<td>
															{{ $issuance_detail->item_id }}
															<input name="item_id[]" type="text" value="{{ $issuance_detail->item_id }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->sku }}
															<input name="sku[]" type="text" value="{{ $issuance_detail->sku }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->item()->withTrashed()->first()->name }}
															<input name="sku[]" type="text" value="{{ $issuance_detail->item()->withTrashed()->first()->name }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->item->type->name }}
														</td>
														<td class="text-end">
															<input name="price[]" type="number" step="0.1" class="text-end border-0 price-info" value="{{ $issuance_detail->item->price ?? 0 }}" readonly>
															<input name="cost[]" type="number" step="0.1" class="form-control form-control-sm bg-light" value="{{ $issuance_detail->item->price ?? 0 }}" readonly hidden>
														</td>
														<td>
															<input name="quantity[]" 
																{{-- type="number" step="1" value="{{ $issuance_detail->quantity }}" min="1" max="{{ $issuance_detail->item->is_inventory == 0 ? 7777777 : $issuance_detail->item->inventory }}" onclick="this.select()" class="text-end" --}}
																type="number" step="1" value="{{ $issuance_detail->quantity }}" min="1" max="{{ $issuance_detail->item->is_inventory == 0 ? 7777777 : $issuance_detail->quantity }}" onclick="this.select()" class="text-end"
																style="width:100%;"
																oninput="
																	this.value = this.value < 1 ? 1 : Math.min(this.value, this.max);
																	var price = {{ $issuance_detail->item->price }};  // price as a number, no .toFixed(2)
																	var quantity = parseFloat(this.value); 
																	var subtotal = (price * quantity);  // Perform calculation without rounding here
																	this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
																" 
															>
														</td>
														<td class="text-end">
															<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="{{ number_format(($issuance_detail->item->price ?? 0) * ($issuance_detail->quantity ?? 1), 2, '.', '') }}" readonly>
														</td>
														<td>&nbsp;</td>
													</tr>
												@endforeach
											</tbody>
											
											<tbody id="totals_rows">
												<!-- Selected items will be appended here -->
												<div id="computation-row">
													<tr style="pointer-events: none;">
														<td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Net Total</td>
														<td class="text-end"><input type="number" name="net_total" value="{{ $transaction->net_total }}" class="text-end border-0" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: auto;" class="table-borderless" hidden>
														<td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">VAT (%)</td>
														<td class="text-end"><input type="number" name="vat" value="12" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" ></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: none;" hidden>
														<td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Grand Total</td>
														<td class="text-end"><input type="number" name="grand_total" value="0.00" class="text-end border-0 fw-bold" style="font-size:17px;" readonly></td>
														<td>&nbsp;</td>
													</tr>
												</div>
											</tbody>
										</table>
									</div>
								</div>

								{{-- <div class="divider text-uppercase divider-center"><small>Reference</small></div>

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
													<th>Cost</th>
													<th width="10%">Stock</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<!-- Results will be appended here via AJAX -->
											</tbody>
										</table>
									</div>
								</div> --}}


								{{-- <div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover" id="selected_items_table">
											<thead>
												<tr>
													<th width="1%"></th>
													<th width="10%">ID</th>
													<th width="15%">SKU</th>
													<th>Item Title</th>													
													<th width="10%">Stock</th>
													<th width="15%">Cost</th>
													<th width="15%" class="price-info">Price</th>
													<th width="20%">Quantity</th>
												</tr>
											</thead>
											<tbody>
												@foreach($issuance_details as $issuance_detail)
													<tr>
														<td>
															<button type="button" class="btn btn-outline-danger remove-item-btn" data-id="{{ $issuance_detail->book_id }}" data-sku="{{ $issuance_detail->sku }}" data-name="{{ $issuance_detail->book()->withTrashed()->first()->name }}" data-cost="{{ $issuance_detail->book->cost ?? 0.00 }}"><i class="bi-trash remove-item-btn"></i></button>
														</td>
														<td>
															{{ $issuance_detail->book_id }}
															<input name="book_id[]" type="text" value="{{ $issuance_detail->book_id }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->sku }}
															<input name="sku[]" type="text" value="{{ $issuance_detail->sku }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->book()->withTrashed()->first()->name }}
															<input type="text" value="{{ $issuance_detail->book()->withTrashed()->first()->name }}" hidden>
														</td>
														<td>
															{{ $issuance_detail->book->inventory }}
														</td>
														<td>
															<input name="cost[]" name="cost[]" type="number" step="1" class="form-control form-control-sm bg-light" value="{{ $issuance_detail->cost }}" readonly>
														</td>
														<td class="price-info">
															<input name="price[]" name="price[]" type="number" step="1" class="form-control form-control-sm bg-light" value="{{ $issuance_detail->price }}" readonly>
														</td>
														<td>
															<input name="quantity[]" name="quantity[]" type="number" step="1" class="form-control form-control-sm" value="{{ $issuance_detail->quantity }}" min="1" max="{{ $issuance_detail->book->inventory }}" oninput="this.value = this.value < 1 ? 1 : Math.min(this.value, this.max);" onclick="select()">
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>

								<div class="divider text-uppercase divider-center"><small>Reference</small></div>

								<div class="form-group row">
									<div class="col-md-11">
										<input type="text" class="form-control" id="item_search" name="item_search" placeholder="Search item via ID or book title .." onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
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
													<th>Item Title</th>
													<th width="15%">Stock</th>
													<th width="15%">Cost</th>
													<th width="15%" class="price-info">Price</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<!-- Results will be appended here via AJAX -->
											</tbody>
										</table>
									</div>
								</div> --}}

								<div class="form-group row">
									<div class="col-sm-10">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="{{ route('issuance.transactions.index') }}" class="btn btn-light">Back</a>
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
			jQuery(".select-tags").select2({
				tags: true
			});
		});
	</script>

	<script>
		// Handle AJAX search and displaying results
		jQuery(document).ready(function() {
			$ = jQuery;

			$('#item_search').on('input', function() {
				let searchQuery = $(this).val();
				if (searchQuery.length) {
					$.ajax({
						url: '{{ route("issuance.transactions.search-item") }}',
						method: 'GET',
						data: { query: searchQuery },
						success: function(data) {
							console.log(data);
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
											<td>${(parseFloat(item.price) || 0).toFixed(2)}</td>
											<td>${item.inventory}</td>
											<td><button type="button" name="insert_selected[]" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}" data-unit="${item.unit}" data-inventory="${item.inventory}" data-price="${item.price}" data-is_inventory="${item.is_inventory}">Add</button></td>
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
			let price = isNaN(parseFloat(target.getAttribute('data-price'))) ? 0.00 : parseFloat(target.getAttribute('data-price'));
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
					actionCell.innerHTML = '<button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="'+id+'" data-sku="'+sku+'" data-name="'+name+'" data-unit="'+unit+'" data-price="'+price+'"><i class="bi-trash"></i></button>';

					let idCell = newRow.insertCell(1);
					idCell.innerHTML = id + '<input name="item_id[]" type="text" value="' + id +'" hidden>';

					let skuCell = newRow.insertCell(2);
					skuCell.innerHTML = sku + '<input name="sku[]" type="text" value="' + sku +'" hidden>';

					let nameCell = newRow.insertCell(3);
					nameCell.textContent = name;

					let unitCell = newRow.insertCell(4);
					unitCell.textContent = unit;

					let priceCell = newRow.insertCell(5);
					priceCell.innerHTML = `
						<input name="price[]" type="number" step="0.1" class="text-end border-0 price-info" value="${price.toFixed(2)}" readonly>
						<input name="cost[]" type="number" step="0.1" class="form-control form-control-sm bg-light" value="${price.toFixed(2)}" readonly hidden>
					`;

					let quantityCell = newRow.insertCell(6);
					quantityCell.innerHTML = `
						<input name="quantity[]" 
							type="number" step="1" value="1" min="1" ${is_inventory == 0 ? 'max="7777777"' : `max="${inventory}"`} onclick="this.select()" class="text-end"
							style="width:100%;"
							oninput="
								this.value = this.value < 1 ? 1 : Math.min(this.value, this.max);
								var price = ${price};  // price as a number, no .toFixed(2)
								var quantity = parseFloat(this.value); 
								var subtotal = (price * quantity);  // Perform calculation without rounding here
								this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
							" 
						>
					`;

					let subtotalCell = newRow.insertCell(7);
					subtotalCell.innerHTML = `<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="${price.toFixed(2)}" readonly>`;
					
					let extraCell = newRow.insertCell(8);
					extraCell.innerHTML = '&nbsp;';

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


		//Calculations

		function updateTotals(){
			// alert('asd');
			
			// Select all rows in the selected items table (excluding the computation row)
			const selectedItemsRows = document.querySelectorAll('#selected_items_table tbody tr');

			let netTotal = 0;
			
			// Loop through each row to sum the subtotals
			selectedItemsRows.forEach(row => {
				const subtotalInput = row.querySelector('input[name="subtotal[]"]');
				if (subtotalInput) {
					netTotal += parseFloat(subtotalInput.value);
				}
			});

			// Get VAT value (make sure it's a number and within a reasonable range)
			let vatPercentage = parseFloat(document.querySelector('input[name="vat"]').value) || 0;
			
			// Calculate the VAT amount
			let vatAmount = (netTotal * vatPercentage) / 100;
			
			// Calculate the grand total (net total + VAT)
			let grandTotal = netTotal + vatAmount;

			// Update the computed values in the table
			document.querySelector('input[name="net_total"]').value = netTotal.toFixed(2);
			document.querySelector('input[name="grand_total"]').value = grandTotal.toFixed(2);
		}


		document.addEventListener('input', function(event) {
			if (event.target.matches('input[name="vat"]') || 
				event.target.matches('input[name="quantity[]"]') || 
				event.target.matches('input[name="remove_selected[]"]')
			) {
				updateTotals();
			}
		});
		
		document.addEventListener('click', function(event) {
			if (event.target.closest('button[name="remove_selected[]"]') ||
				event.target.closest('button[name="insert_selected[]"]')) {
				updateTotals(); 
			}
		});
	</script>


	{{-- AUTOLOAD ITEMS FROM REQUISITION --}}
	<script>
		$(document).ready(function () {

			// When user types RIS #
			$('#ris_number').on('input', function () {
				const query = $(this).val().trim();

				if (query.length === 0) {
					$('#selected_items_table tbody').html(`
						<tr><td class="text-center text-danger" colspan="100%">Type RIS number to search</td></tr>
					`);
					return;
				}

				$.ajax({
					url: '{{ route("issuance.transactions.search-ris-number") }}',
					method: 'GET',
					data: { q: query },
					success: function (data) {

						// Populate datalist
						const options = data.results;
						const datalist = $('#ris_number_list');
						datalist.empty();

						options.forEach(item => {
							datalist.append(`<option value="${item.ref_no}">`);
						});

						// If at least one result, fill the vehicle select
						if (options.length > 0) {
							const last = options[options.length - 1];

							// Assuming last.vehicle_id is JSON encoded array of vehicle IDs
							let vehicleIDs = JSON.parse(last.vehicle_id);

							let select = $('.vehicle_id');

							// Clear current selection
							select.val(null).trigger('change');

							// mark matching vehicles as selected
							vehicleIDs.forEach(id => {
								select.find(`option[value="${id}"]`).prop('selected', true);
							});

							// refresh Select2 or multi-select plugin
							select.trigger('change');
						}

						// If RIS exists → load its items
						if (options.length > 0) {

							$.ajax({
								url: '{{ route("issuance.transactions.search-requested-item") }}',
								method: 'GET',
								data: { q: query },
								success: function (data) {

									let body = $('#selected_items_rows');
									body.html(''); // clear

									if (data.results.length) {

										data.results.forEach(item => {
											body.append(`
												<tr>
													<td></td>
													<td>${item.item_id}
														<input name="item_id[]" type="hidden" value="${item.item_id}">
													</td>
													<td>${item.sku}
														<input name="sku[]" type="hidden" value="${item.sku}">
													</td>
													<td>${item.item_name}</td>
													<td>${item.unit}</td>
													<td class="text-end">
														<input name="price[]" type="number" step="0.1" class="text-end border-0 price-info" value="${(parseFloat(item.price) || 0).toFixed(2)}" readonly>
														<input name="cost[]" type="number" step="0.1" class="form-control form-control-sm bg-light" value="${(parseFloat(item.price) || 0).toFixed(2)}" readonly hidden>
													</td>
													<td class="text-end">
														<input name="quantity[]" 
															type="number" 
															step="1"
															value="${item.remaining}"
															min="1" ${item.is_inventory == 0 ? 'max="7777777"' : `max="${item.quantity}"`}
															onclick="this.select()"
															class="text-end"
															oninput="
																this.value = this.value < 1 ? 1 : Math.min(this.value, this.max);
																var price = ${item.price};  // price as a number, no .toFixed(2)
																var quantity = parseFloat(this.value); 
																var subtotal = (price * quantity);  // Perform calculation without rounding here
																this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
															"
														>
													</td>
													<td class="text-end">
														<input name="subtotal[]" 
															type="number"
															value="${(item.remaining * item.price).toFixed(2)}"
															class="text-end border-0 subtotal"
															readonly>
													</td>
													<td></td>
												</tr>
											`);
										});

										computeTotals();

									} else {
										body.html(`
											<tr><td class="text-center text-danger" colspan="100%">No items found</td></tr>
										`);
									}
								}
							});

						} else {
							$('#selected_items_rows').html(`
								<tr><td class="text-center text-danger" colspan="100%">No RIS found</td></tr>
							`);
						}
					}
				});
			});

		});

		// Recompute subtotal per row
		function recalcRow(input) {
			let row = $(input).closest('tr');
			let qty = parseFloat($(input).val()) || 0;
			let price = parseFloat(row.find('input[name="cost[]"]').val()) || 0;

			row.find('.subtotal').val((qty * price).toFixed(2));

			computeTotals();
		}

		// Compute totals
		function computeTotals() {
			let netTotal = 0;

			$('.subtotal').each(function () {
				netTotal += parseFloat($(this).val()) || 0;
			});

			$('input[name="net_total"]').val(netTotal.toFixed(2));
		}
	</script>
@endsection