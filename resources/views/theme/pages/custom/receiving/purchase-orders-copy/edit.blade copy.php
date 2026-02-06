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
                        <li class="breadcrumb-item"><a href="{{ route('receiving.transactions.index') }}">{{ $page->name }}</a></li>
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
                            
							<form method="post" action="{{ route('receiving.transactions.update', $transaction->id) }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								@method('put')

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Printers / Suppliers</label>
									<div class="col-sm-10">
										<select title="Printers/Suppliers are auto-generated" id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" required disabled>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
													{{ $supplier->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Date Received</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_received" name="date_received" value="{{ $transaction->date_received }}" required>
									</div>
								</div>
								<div id="book_cover_input" class="form-group row" @if(!is_null($transaction->book_cover)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Item Cover</label>
									<div class="col-sm-10">
										<input id="book_cover" name="book_cover" class="input-file" type="file" data-show-preview="false" accept=".png, .jpg">
									</div>
								</div>
								
								<div id="book_cover_display" class="form-group row" @if(is_null($transaction->book_cover)) style="display: none" @endif>
									<label class="col-sm-2 col-form-label">Item Cover</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="bi-file-earmark"></i>
												</span>
											</div>
											<input type="text" value="{{ basename($transaction->book_cover) }}" class="form-control" readonly>
											<div class="input-group-append">
												<button type="button" class="btn btn-outline-danger" onclick="remove_file('#book_cover_display', '#book_cover_input')">
													<i class="bi-trash"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
								
								<div id="attachments_input" class="form-group row" @if(!is_null($transaction->attachments)) style="display: none" @endif>
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
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Remarks</label>
									<div class="col-sm-10">
										<textarea class="form-control" id="remarks" name="remarks">{{ $transaction->remarks }}</textarea>
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
													<th>Item Title</th>
													<th width="20%">Cost</th>
													<th width="20%">Qty Ordered</th>
													<th width="20%">Qty Received</th>
												</tr>
											</thead>
											<tbody>
												@foreach($receiving_details as $receiving_detail)
													<tr>
														<td>
															<button type="button" class="btn btn-outline-danger remove-item-btn" data-id="{{ $receiving_detail->book_id }}" data-sku="{{ $receiving_detail->sku }}" data-name="{{ $receiving_detail->book()->withTrashed()->first()->name }}" data-cost="{{ $receiving_detail->book()->withTrashed()->first()->total_cost ?? 0.00 }}"><i class="bi-trash"></i></button>
														</td>
														<td>
															{{ $receiving_detail->book_id }}
															<input name="book_id[]" type="text" value="{{ $receiving_detail->book_id }}" hidden>
														</td>
														<td>
															{{ $receiving_detail->sku }}
															<input name="sku[]" type="text" value="{{ $receiving_detail->sku }}" hidden>
														</td>
														<td>
															{{ $receiving_detail->book()->withTrashed()->first()->name }}
															<input name="sku[]" type="text" value="{{ $receiving_detail->book()->withTrashed()->first()->name }}" hidden>
														</td>
														<td>
															{{ number_format($receiving_detail->book->total_cost ?? 0, 2) }}
														</td>
														<td>
															<input name="order[]" name="order[]" type="number" step="1" class="form-control form-control-sm" value="{{ $receiving_detail->order }}" min="1" oninput="this.value = this.value < 1 ? 1 : this.value" onclick="select()">
														</td>
														<td>
															<input name="quantity[]" name="quantity[]" type="number" step="1" class="form-control form-control-sm" value="{{ $receiving_detail->quantity }}" min="1" oninput="this.value = this.value < 1 ? 1 : this.value" onclick="select()">
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
													<th>Cost</th>
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
										<a href="{{ route('receiving.transactions.index') }}" class="btn btn-light">Back</a>
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
						url: '{{ route("receiving.transactions.search-item") }}',
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
											<td>${(parseFloat(item.total_cost) || 0).toFixed(2)}</td>
											<td><button type="button" class="btn btn-outline-primary add-item-btn" data-id="${item.id}" data-sku="${item.sku}" data-name="${item.name}"  data-copies="${item.copies}" data-cost="${item.total_cost}">Add</button></td>
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
			let copies = target.getAttribute('data-copies');
			let cost = isNaN(parseFloat(target.getAttribute('data-cost'))) ? 0.00 : parseFloat(target.getAttribute('data-cost'));

			// Handle adding items to the selected list
			if (target.classList.contains('add-item-btn')) {
				let selectedTableBody = document.querySelector('#selected_items_table tbody');
				
				// Check if the item already exists in the selected items table
				let exists = Array.from(selectedTableBody.querySelectorAll('tr')).some(row => {
					return row.querySelector('input[name="book_id[]"]').value === id;
				});

				if (!exists) {
					// Create a new row for the selected items table
					let newRow = selectedTableBody.insertRow();

					// Insert cells and their content
					let actionCell = newRow.insertCell(0);
					actionCell.innerHTML = '<button type="button" class="btn btn-outline-danger remove-item-btn" data-id="'+id+'" data-sku="'+sku+'" data-name="'+name+'" data-copies="'+copies+'" data-cost="'+cost+'"><i class="bi-trash"></i></button>';

					let idCell = newRow.insertCell(1);
					idCell.innerHTML = id + '<input name="book_id[]" type="text" value="' + id +'" hidden>';

					let skuCell = newRow.insertCell(2);
					skuCell.innerHTML = sku + '<input name="sku[]" type="text" value="' + sku +'" hidden>';

					let nameCell = newRow.insertCell(3);
					nameCell.textContent = name;

					let costCell = newRow.insertCell(4);
					costCell.textContent = cost.toFixed(2);

					let orderCell = newRow.insertCell(5);
					orderCell.innerHTML = '<input name="order[]" type="number" step="1" class="form-control form-control-sm" value="' + copies +'" min="1" oninput="this.value = this.value < 1 ? 1 : this.value" onclick="select()">';

					let quantityCell = newRow.insertCell(6);
					quantityCell.innerHTML = '<input name="quantity[]" type="number" step="1" class="form-control form-control-sm" value="1" min="1" oninput="this.value = this.value < 1 ? 1 : this.value" onclick="select()">';

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

	</script>

	<script>
		function remove_file(remove_div, show_div){
			$(remove_div).hide();
			$(show_div).show();
		}
	</script>
	
@endsection