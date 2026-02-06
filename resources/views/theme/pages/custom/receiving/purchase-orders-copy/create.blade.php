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
                        <li class="breadcrumb-item">Create</li>
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
                            
							<form method="post" action="{{ route('receiving.purchase-orders.store') }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">P.O. #</label>
									<div class="col-sm-10">
										<input type="text" id="ref_no" name="ref_no" class="form-control" autocomplete="off" placeholder="Type to search P.O. #" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }" required>
										@error('ref_no')
											<span class="text-danger">{{ $message }}</span>
										@enderror
									</div>

								</div>
								<div class="form-group row" hidden>
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										<select id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" required>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option selected value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Date Ordered</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_ordered" name="date_ordered" value="<?= date('Y-m-d'); ?>" required>
									</div>
								</div>
								{{-- <div class="form-group row">
									<label class="col-sm-2 col-form-label">Attachments</label>
									<div class="col-sm-10">
										<input id="attachments" name="attachments[]" class="input-file file-loading" type="file" data-show-upload="false" data-show-caption="true" data-show-preview="false" multiple>
									</div>
								</div> --}}
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Remarks</label>
									<div class="col-sm-10">
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
													<th width="15%">SKU</th>
													<th width="20%">Item</th>
													<th width="10%">Unit</th>
													<th width="15%">Price</th>
													<th width="10%">Qty</th>
													<th width="15%" class="text-end">Subtotal</th>
													<th width="5%"></th>
												</tr>
											</thead>
											<tbody>
												<!-- Selected items will be appended here -->
												<div id="computation-row">
													<tr style="pointer-events: none;">
														<td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Net Total</td>
														<td class="text-end"><input type="number" name="net_total" value="0.00" class="text-end border-0" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: auto;" class="table-borderless" hidden>
														<td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">VAT (%)</td>
														<td class="text-end"><input type="number" name="vat" value="0" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" ></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: none;">
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
													<th>Price</th>
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
											<td><button type="button" name="insert_selected[]" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}" data-unit="${item.unit}" data-price="${item.price}">Add</button></td>
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

					let quantityCell = newRow.insertCell(6);
					quantityCell.innerHTML = `
						<input name="quantity[]" 
							type="number" step="1" value="1" min="1" onclick="this.select()"
							oninput="
								this.value = this.value < 1 ? 1 : this.value; 
								var price = ${price};  // price as a number, no .toFixed(2)
								var quantity = parseFloat(this.value); 
								var subtotal = (price * quantity);  // Perform calculation without rounding here
								this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
							" 
						>
					`;

					let subtotalCell = newRow.insertCell(7);
					subtotalCell.className = "text-end";
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

				// Optionally, return the item to the search results table
				// let newRow = searchResultsTableBody.insertRow();
				// let idCell = newRow.insertCell(0);
				// idCell.textContent = id;
				// let skuCell = newRow.insertCell(1);
				// skuCell.textContent = sku;
				// let nameCell = newRow.insertCell(2);
				// nameCell.textContent = name;
				// let actionCell = newRow.insertCell(3);
				// actionCell.innerHTML = '<button class="btn btn-primary btn-sm add-item-btn" data-id="'+id+'" data-sku="'+sku+'" data-name="'+name+'">Add</button>';
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
	
@endsection