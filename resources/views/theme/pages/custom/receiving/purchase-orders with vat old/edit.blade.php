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
                        <li class="breadcrumb-item"><a href="{{ route('receiving.purchase-orders.index') }}">{{ $page->name }}</a></li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Order Details</div>

                        <div class="card-body">
                            
							<form method="post" action="{{ route('receiving.purchase-orders.update', $purchase_order->id) }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								@method('put')

								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">P.O. #</label>
									<div class="col-sm-10">
										<input type="text" id="ref_no" name="ref_no" class="form-control" autocomplete="off" value="{{ $purchase_order->ref_no }}" placeholder="Type to search P.O. #" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }" required>
										@error('ref_no')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										{{-- <select title="Printers/Suppliers are auto-generated" id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" required disabled> --}}
										<select id="supplier_id" name="supplier_id[]" class="form-select" style="width:100%;" required>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option data-vatable="{{ $supplier->is_vatable }}" value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($purchase_order->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
													{{ $supplier->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Date Ordered</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_ordered" name="date_ordered" value="{{ $purchase_order->date_ordered }}" required>
									</div>
								</div>
								{{-- <div id="attachments_input" class="form-group row" @if(!is_null($purchase_order->attachments)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Attachments</label>
									<div class="col-sm-10">
										<input id="attachments" name="attachments[]" class="input-file" type="file" data-show-upload="false" data-show-caption="true" data-show-preview="false" multiple>
									</div>
								</div>
								<div id="attachments_display" class="form-group row" @if(is_null($purchase_order->attachments)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Attachments</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="bi-file-earmark"></i>
												</span>
											</div>
											<input type="text" value="{{ implode(', ', array_map('basename', json_decode($purchase_order->attachments ?? '[]', true))) }}" class="form-control" readonly>
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
										<textarea class="form-control" id="remarks" name="remarks">{{ $purchase_order->remarks }}</textarea>
									</div>
								</div>

								<div class="divider text-uppercase divider-center"><small>Item Details</small></div>
								
								<div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover" id="selected_items_table">
											<thead>
												<tr>
													<th width="1%"></th>
													<th width="5%">ID</th>
													<th width="10%">SKU</th>
													<th width="15%">Item</th>
													<th width="10%">Unit</th>
													<th width="10%">Price</th>
													<th width="10%" class="vat-col text-center" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>VAT({{ env('VAT_RATE') }}%)</th>
													<th width="10%">RIS#</th>
													<th width="10%">Requested Qty</th>
													<th width="10%" class="text-end">Subtotal</th>
													<th width="5%"></th>
												</tr>
											</thead>
											<tbody>
												@foreach($purchase_order_details as $purchase_order_detail)
													<tr>
														<td>
															<button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="{{ $purchase_order_detail->item_id }}" data-sku="{{ $purchase_order_detail->sku }}" data-name="{{ $purchase_order_detail->item()->withTrashed()->first()->name }}" data-price="{{ $purchase_order_detail->item()->withTrashed()->first()->price ?? 0.00 }}"><i class="bi-trash"></i></button>
														</td>
														<td>
															{{ $purchase_order_detail->item_id }}
															<input name="item_id[]" type="text" value="{{ $purchase_order_detail->item_id }}" hidden>
														</td>
														<td>
															{{ $purchase_order_detail->sku }}
															<input name="sku[]" type="text" value="{{ $purchase_order_detail->sku }}" hidden>
														</td>
														<td>
															{{ $purchase_order_detail->item()->withTrashed()->first()->name }}
															{{-- <input name="sku[]" type="text" value="{{ $purchase_order_detail->item()->withTrashed()->first()->name }}" hidden> --}}
														</td>
														<td>
															{{ $purchase_order_detail->item->type->name }}
														</td>
														<td>
															{{ number_format($purchase_order_detail->item->price ?? 0, 2) }}
														</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>
															<input type="hidden" name="vat_rate[]" class="vat-input" value="{{ $purchase_order_detail->vat }}">
															<input type="number" name="vat_inclusive_price[]" 
																class="vat-inclusive-price border-0 text-end"
																value="{{ $purchase_order_detail->vat_inclusive_price }}" readonly style="width:80px; @if($purchase_order->supplier->is_vatable == 0) display:none; @endif">
														</td>
														<td>
															{{ $purchase_order_detail->ris_no }}
															<input name="ris_no[]" type="text" value="{{ $purchase_order_detail->ris_no }}" hidden>
														</td>
														<td>
															<input name="quantity[]" 
																type="number" step="1" class="text-end" value="{{ $purchase_order_detail->quantity }}" min="1" onclick="this.select()"
																oninput="recalculateRow(this)"
																{{-- oninput="
																	this.value = this.value < 1 ? 1 : this.value; 
																	var price = {{ $purchase_order_detail->item->price }};  // price as a number, no .toFixed(2)
																	var quantity = parseFloat(this.value); 
																	var subtotal = (price * quantity);  // Perform calculation without rounding here
																	this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
																"  --}}
															>
														</td>
														<td class="text-end">
															<input class="subtotal text-end border-0" name="subtotal[]" type="number" 
															value="{{ 
																$purchase_order->supplier->is_vatable == 0 ?
																	number_format(($purchase_order_detail->price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
																:
																	number_format(($purchase_order_detail->vat_inclusive_price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
															}}" 
															readonly>
															<input class="orig-subtotal" name="orig-subtotal[]" type="hidden" value="{{ ($purchase_order_detail->price ?? 0) * ($purchase_order_detail->quantity ?? 1) }}">
														</td>
														<td>
															<input type="hidden" name="item_purpose[]" value="{{ $purchase_order_detail->purpose }}">
															<input type="hidden" name="item_remarks[]" value="{{ $purchase_order_detail->remarks }}">
														</td>
													</tr>
												@endforeach
													
												{{-- COMPUTATIONS --}}
												<div id="computation-row">
													<tr style="pointer-events: none;">
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="7"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Net Total</td>
														<td class="text-end"><input type="number" name="net_total" value="{{ $purchase_order->net_total }}" class="text-end border-0" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: auto;" class="table-borderless" hidden>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="7"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">VAT (%)</td>
														<td class="text-end"><input type="number" id="vat" name="vat" value="{{ $purchase_order->vat > 0 ? $purchase_order->vat : 0 }}" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: none;">
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="7"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Grand Total</td>
														<td class="text-end"><input type="number" name="grand_total" value="{{ $purchase_order->grand_total }}" class="text-end border-0 fw-bold" style="font-size:17px;" readonly></td>
														<td>&nbsp;</td>
													</tr>
												</div>
											</tbody>
										</table>
									</div>
								</div>

								<div class="divider text-uppercase divider-center"><small>Reference</small></div>

								<div class="form-group row">
									<div class="col-md-11">
										<input type="text" class="form-control" id="item_search" name="item_search" placeholder="Search item via ID, item name, or RIS # .." onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
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
													<th>Price</th>
													<th>RIS#</th>
													<th>Requested Qty</th>
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
									<div class="col-sm-10">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="{{ route('receiving.purchase-orders.index') }}" class="btn btn-light">Back</a>
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
						url: '{{ route("receiving.purchase-orders.search-item") }}',
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
											<td>${item.ris_no ?? 'N/A'}</td>
											<td>${item.quantity ?? 'N/A'}</td>
											<td><button type="button" name="insert_selected[]" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}" data-unit="${item.unit}" data-price="${item.price}" data-ris_no="${item.ris_no ?? 'N/A'}" data-quantity="${item.quantity ?? 1}" data-purpose="${item.purpose ?? ''}" data-remarks="${item.remarks ?? ''}">Add</button></td>
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
			let ris_no = target.getAttribute('data-ris_no');
			let quantity = target.getAttribute('data-quantity') ?? 1;
			let purpose = target.getAttribute('data-purpose');
			let remarks = target.getAttribute('data-remarks');

			let isVatable = $('#supplier_id').find(':selected').data('vatable'); // 1 or 0
			let vatPrice = 0;

			if (isVatable == 1) {
				vatPrice = price * (1 + VAT_RATE / 100);
			}

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
					priceCell.textContent = price.toFixed(2);

					let vatCell = newRow.insertCell(6);
					vatCell.className = 'vat-col';
					vatCell.innerHTML = `
						<input type="hidden" name="vat_rate[]" class="vat-input" value="0">
						<input type="number" name="vat_inclusive_price[]" 
							class="vat-inclusive-price border-0 text-end"
							value="0" readonly style="width:80px; display:none;">
					`;

					let risCell = newRow.insertCell(7);
					risCell.innerHTML = ris_no + '<input name="ris_no[]" type="text" value="' + ris_no +'" hidden>';

					let quantityCell = newRow.insertCell(8);
					quantityCell.innerHTML = `
						<input name="quantity[]" class="text-end"
							type="number" step="1" value="${quantity}" min="1" onclick="this.select()"
							oninput="recalculateRow(this)"
						>
					`;

					let subtotalCell = newRow.insertCell(9);
					subtotalCell.className = "text-end";
					subtotalCell.innerHTML = `
						<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="${price.toFixed(2) * quantity}" readonly>
						<input class="orig-subtotal" name="orig-subtotal[]" type="hidden" value="${(price * quantity).toFixed(2)}">
					`;
					
					let extraCell = newRow.insertCell(10);
					extraCell.innerHTML = `
						<input type="hidden" name="item_purpose[]" value="${purpose}">
						<input type="hidden" name="item_remarks[]" value="${remarks}">
					`;

					// APPLY VAT IMMEDIATELY IF SUPPLIER IS VATABLE
					applyVatToRow($(newRow));
					calculateGrandTotal();


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
				const subtotalInput = row.querySelector('input[name="orig-subtotal[]"]');
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



		//FOR VAT
		const VAT_RATE = {{ env('VAT_RATE') }};

		// When supplier changes
		$('#supplier_id').on('change', function () {

			let isVatable = $(this).find(':selected').data('vatable'); // 1 or 0

			if (isVatable == 1) {
				// SHOW VAT COLUMN
				$('.vat-col').show();
			} else {
				// HIDE VAT COLUMN
				$('.vat-col').hide();
			}

			// Update all rows
			$('#selected_items_table tbody tr').each(function () {

				let row = $(this);
				let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
				let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;

				let vatHeader = row.find('#vat');
				let vatInput = row.find('.vat-input');
				let vatIncl = row.find('.vat-inclusive-price');
				let subtotalInput = row.find('.subtotal');

				if (isVatable == 1) {

					let newPrice = price * (1 + VAT_RATE / 100);

					vatHeader.val(VAT_RATE);
					vatInput.val(VAT_RATE);
					vatIncl.val(newPrice.toFixed(2)).show();

					subtotalInput.val((newPrice * qty).toFixed(2));

				} else {

					vatHeader.val(0);
					vatInput.val(0);
					vatIncl.val(0).hide();

					subtotalInput.val((price * qty).toFixed(2));
				}
			});

			calculateGrandTotal();
		});


		// Recalculate when quantity changes
		function recalculateRow(input) {

			let row = $(input).closest('tr');
			let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
			let qty = parseFloat($(input).val()) || 1;

			let isVatable = $('#supplier_id').find(':selected').data('vatable');

			let vatIncl = row.find('.vat-inclusive-price');
			let subtotal = row.find('.subtotal');
			let origSubtotal = row.find('.orig-subtotal');

			// ALWAYS store original price subtotal
			origSubtotal.val((price * qty).toFixed(2));

			if (isVatable == 1) {
				let newPrice = price * (1 + VAT_RATE / 100);
				vatIncl.val(newPrice.toFixed(2)).show();
				subtotal.val((newPrice * qty).toFixed(2)); // VAT subtotal for display
			} else {
				vatIncl.hide();
				subtotal.val((price * qty).toFixed(2)); // display original subtotal if not vatable
			}

			calculateGrandTotal();
		}

		// Compute totals
		function calculateGrandTotal() {

			let net = 0;
			let displayTotal = 0;

			let isVatable = $('#supplier_id').find(':selected').data('vatable');

			$('#selected_items_table tbody tr').each(function () {

				let orig = parseFloat($(this).find('.orig-subtotal').val()) || 0;
				let disp = parseFloat($(this).find('.subtotal').val()) || 0;

				net += orig;          // ALWAYS VAT-FREE ORIGINAL TOTAL
				displayTotal += disp; // VAT-INCLUDED OR ORIGINAL, depends on supplier
			});

			$('input[name="net_total"]').val(net.toFixed(2));
			$('input[name="grand_total"]').val(displayTotal.toFixed(2));
		}


		function applyVatToRow(row) {

			const VAT_RATE = {{ env('VAT_RATE') }};
			let isVatable = $('#supplier_id').find(':selected').data('vatable'); 

			let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
			let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;

			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let subtotalInput = row.find('.subtotal');
			let orig = row.find('.orig-subtotal');

			// ALWAYS set original subtotal (VAT FREE)
			orig.val((price * qty).toFixed(2));

			if (isVatable == 1) {

				$('.vat-col').show();

				let newPrice = price * (1 + VAT_RATE / 100);

				vatInput.val(VAT_RATE);
				vatIncl.val(newPrice.toFixed(2)).show();

				subtotalInput.val((newPrice * qty).toFixed(2));   // VAT subtotal

			} else {

				$('.vat-col').hide();

				vatInput.val(0);
				vatIncl.val(0).hide();

				subtotalInput.val((price * qty).toFixed(2));      // NON-VAT subtotal
			}
		}
	</script>
@endsection

{{-- @section('pagejs')
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
						url: '{{ route("receiving.purchase-orders.search-item") }}',
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
											<td>${item.ris_no ?? 'N/A'}</td>
											<td>${item.quantity ?? 'N/A'}</td>
											<td><button type="button" name="insert_selected[]" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}" data-unit="${item.unit}" data-price="${item.price}" data-ris_no="${item.ris_no ?? 'N/A'}" data-quantity="${item.quantity ?? 1}">Add</button></td>
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
			let ris_no = target.getAttribute('data-ris_no');
			let quantity = target.getAttribute('data-quantity') ?? 1;

			let isVatable = $('#supplier_id').find(':selected').data('vatable'); // 1 or 0
			let vatPrice = 0;

			if (isVatable == 1) {
				vatPrice = price * (1 + VAT_RATE / 100);
			}

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
					priceCell.textContent = price.toFixed(2);

					let vatCell = newRow.insertCell(6);
					vatCell.className = 'vat-col';
					vatCell.innerHTML = `
						<input type="hidden" name="vat_rate[]" class="vat-input" value="0">
						<input type="number" name="vat_inclusive_price[]" 
							class="vat-inclusive-price border-0 text-end"
							value="0" readonly style="width:80px; display:none;">
					`;

					let risCell = newRow.insertCell(7);
					risCell.innerHTML = ris_no + '<input name="ris_no[]" type="text" value="' + ris_no +'" hidden>';

					let quantityCell = newRow.insertCell(8);
					quantityCell.innerHTML = `
						<input name="quantity[]" class="text-end"
							type="number" step="1" value="${quantity}" min="1" onclick="this.select()"
							oninput="recalculateRow(this)"
						>
					`;

					let subtotalCell = newRow.insertCell(9);
					subtotalCell.className = "text-end";
					subtotalCell.innerHTML = `
						<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="${price.toFixed(2) * quantity}" readonly>
						<input class="orig-subtotal" name="orig-subtotal[]" type="hidden" value="${(price * quantity).toFixed(2)}">
					`;
					
					let extraCell = newRow.insertCell(10);
					extraCell.innerHTML = '&nbsp;';

					// APPLY VAT IMMEDIATELY IF SUPPLIER IS VATABLE
					applyVatToRow($(newRow));
					calculateGrandTotal();


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
				const subtotalInput = row.querySelector('input[name="orig-subtotal[]"]');
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



		//FOR VAT
		const VAT_RATE = {{ env('VAT_RATE') }};

		// When supplier changes
		$('#supplier_id').on('change', function () {

			let isVatable = $(this).find(':selected').data('vatable'); // 1 or 0

			if (isVatable == 1) {
				// SHOW VAT COLUMN
				$('.vat-col').show();
			} else {
				// HIDE VAT COLUMN
				$('.vat-col').hide();
			}

			// Update all rows
			$('#selected_items_table tbody tr').each(function () {

				let row = $(this);
				let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
				let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;

				let vatHeader = row.find('#vat');
				let vatInput = row.find('.vat-input');
				let vatIncl = row.find('.vat-inclusive-price');
				let subtotalInput = row.find('.subtotal');

				if (isVatable == 1) {

					let newPrice = price * (1 + VAT_RATE / 100);

					vatHeader.val(VAT_RATE);
					vatInput.val(VAT_RATE);
					vatIncl.val(newPrice.toFixed(2)).show();

					subtotalInput.val((newPrice * qty).toFixed(2));

				} else {

					vatHeader.val(0);
					vatInput.val(0);
					vatIncl.val(0).hide();

					subtotalInput.val((price * qty).toFixed(2));
				}
			});

			calculateGrandTotal();
		});


		// Recalculate when quantity changes
		function recalculateRow(input) {

			let row = $(input).closest('tr');
			let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
			let qty = parseFloat($(input).val()) || 1;

			let isVatable = $('#supplier_id').find(':selected').data('vatable');

			let vatIncl = row.find('.vat-inclusive-price');
			let subtotal = row.find('.subtotal');
			let origSubtotal = row.find('.orig-subtotal');

			// ALWAYS store original price subtotal
			origSubtotal.val((price * qty).toFixed(2));

			if (isVatable == 1) {
				let newPrice = price * (1 + VAT_RATE / 100);
				vatIncl.val(newPrice.toFixed(2)).show();
				subtotal.val((newPrice * qty).toFixed(2)); // VAT subtotal for display
			} else {
				vatIncl.hide();
				subtotal.val((price * qty).toFixed(2)); // display original subtotal if not vatable
			}

			calculateGrandTotal();
		}

		// Compute totals
		function calculateGrandTotal() {

			let net = 0;
			let displayTotal = 0;

			let isVatable = $('#supplier_id').find(':selected').data('vatable');

			$('#selected_items_table tbody tr').each(function () {

				let orig = parseFloat($(this).find('.orig-subtotal').val()) || 0;
				let disp = parseFloat($(this).find('.subtotal').val()) || 0;

				net += orig;          // ALWAYS VAT-FREE ORIGINAL TOTAL
				displayTotal += disp; // VAT-INCLUDED OR ORIGINAL, depends on supplier
			});

			$('input[name="net_total"]').val(net.toFixed(2));
			$('input[name="grand_total"]').val(displayTotal.toFixed(2));
		}


		function applyVatToRow(row) {

			const VAT_RATE = {{ env('VAT_RATE') }};
			let isVatable = $('#supplier_id').find(':selected').data('vatable'); 

			let price = parseFloat(row.find('td:nth-child(6)').text()) || 0;
			let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;

			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let subtotalInput = row.find('.subtotal');
			let orig = row.find('.orig-subtotal');

			// ALWAYS set original subtotal (VAT FREE)
			orig.val((price * qty).toFixed(2));

			if (isVatable == 1) {

				$('.vat-col').show();

				let newPrice = price * (1 + VAT_RATE / 100);

				vatInput.val(VAT_RATE);
				vatIncl.val(newPrice.toFixed(2)).show();

				subtotalInput.val((newPrice * qty).toFixed(2));   // VAT subtotal

			} else {

				$('.vat-col').hide();

				vatInput.val(0);
				vatIncl.val(0).hide();

				subtotalInput.val((price * qty).toFixed(2));      // NON-VAT subtotal
			}
		}
	</script>
@endsection --}}