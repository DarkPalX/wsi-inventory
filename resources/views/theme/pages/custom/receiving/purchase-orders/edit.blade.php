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
													<th width="10%" class="vat-col text-center" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>Net of VAT</th>
													<th width="10%">RIS#</th>
													<th width="10%">Purpose</th>
													<th width="10%">Remarks</th>
													<th width="10%">Req Qty</th>
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
															@if($purchase_order->supplier->is_vatable == 0)
																<span class="display-price">{{ number_format($purchase_order_detail->price ?? 0, 2) }}</span>
															@else
																<span class="display-price">{{ number_format($purchase_order_detail->price - $purchase_order_detail->vat_inclusive_price ?? 0, 2) }}</span>
															@endif
															<input type="hidden" name="orig_price[]" value="{{ number_format($purchase_order_detail->price ?? 0, 2) }}">
														</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>
															<input type="hidden" name="vat_rate[]" class="vat-input" value="{{ $purchase_order_detail->vat }}">
															<input type="number" name="vat_inclusive_price[]" 
																class="vat-inclusive-price border-0 text-end"
																value="{{ $purchase_order_detail->vat_inclusive_price }}" readonly style="width:80px; @if($purchase_order->supplier->is_vatable == 0) display:none; @endif">
														</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>
																{{ number_format($purchase_order_detail->price ?? 0, 2) }}
														</td>
														<td>
															{{ $purchase_order_detail->ris_no }}
															<input name="ris_no[]" type="text" value="{{ $purchase_order_detail->ris_no }}" hidden>
														</td>
														<td>
															<input name="po_item_purpose[]" type="text" value="{{ explode('|#|', $purchase_order_detail->purpose)[0] }}">
														</td>
														<td>
															<input name="po_item_remarks[]" type="text" value="{{ explode('|#|', $purchase_order_detail->remarks)[0] }}">
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
																	number_format(($purchase_order_detail->price - $purchase_order_detail->vat_inclusive_price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
															}}" 
															readonly>
															<input class="orig-subtotal" name="orig-subtotal[]" type="hidden" 
															value="{{ 
																$purchase_order->supplier->is_vatable == 0 ?
																	number_format(($purchase_order_detail->price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
																:
																	number_format(($purchase_order_detail->price - $purchase_order_detail->vat_inclusive_price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
															}}">
														</td>
														<td>
															<input type="hidden" name="item_purpose[]" value="{{ explode('|#|', $purchase_order_detail->purpose ?? '')[1] ?? '' }}">
															<input type="hidden" name="item_remarks[]" value="{{ explode('|#|', $purchase_order_detail->remarks ?? '')[1] ?? '' }}">
														</td>
													</tr>
												@endforeach
													
												{{-- COMPUTATIONS --}}
												<div id="computation-row">
													<tr style="pointer-events: none;">
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="9"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">Net Total</td>
														<td class="text-end"><input type="number" name="net_total" value="{{ $purchase_order->net_total }}" class="text-end border-0" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: auto;" class="table-borderless" hidden>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="9"><input name="item_id[]" type="text" value="0" hidden></td>
														<td class="text-end">VAT (%)</td>
														<td class="text-end"><input type="number" id="vat" name="vat" value="{{ $purchase_order->vat > 0 ? $purchase_order->vat : 0 }}" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" readonly></td>
														<td>&nbsp;</td>
													</tr>
													<tr style="pointer-events: none;">
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
														<td colspan="9"><input name="item_id[]" type="text" value="0" hidden></td>
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
		jQuery(document).ready(function(){
			// select Tags
			jQuery(".select-tags").select2({ tags: true });
		});

		// VAT rate
		const VAT_RATE = {{ env('VAT_RATE') }};

		// Handle AJAX search
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
							let resultsTableBody = $('#search_results_table tbody');
							resultsTableBody.html('');
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
											<td>
												<button type="button" class="btn btn-outline-primary add-item-btn"
													data-id="${item.id}"
													data-sku="${item.sku}"
													data-name="${item.name}"
													data-unit="${item.unit}"
													data-price="${item.price}"
													data-ris_no="${item.ris_no ?? 'N/A'}"
													data-quantity="${item.quantity ?? 1}"
													data-purpose="${item.purpose ?? ''}"
													data-remarks="${item.remarks ?? ''}">
													Add
												</button>
											</td>
										</tr>
									`);
								});
							} else {
								resultsTableBody.append('<tr><td class="text-center text-danger" colspan="100%">No items found</td></tr>');
							}
						},
						error: function(xhr) { console.log('Error:', xhr.responseText); }
					});
				} else {
					$('#search_results_table tbody').html('<tr><td class="text-center text-danger" colspan="100%">Search query is empty</td></tr>');
				}
			});
		});

		// Handle adding/removing items
		document.addEventListener('click', function(event) {
			let target = event.target.closest('button');
			if (!target) return;

			// Add item
			if (target.classList.contains('add-item-btn')) {
				let id = target.dataset.id;
				let sku = target.dataset.sku;
				let name = target.dataset.name;
				let unit = target.dataset.unit;
				let price = parseFloat(target.dataset.price) || 0;
				let ris_no = target.dataset.ris_no;
				let quantity = target.dataset.quantity ?? 1;
				let purpose = target.dataset.purpose;
				let remarks = target.dataset.remarks;

				let selectedTableBody = document.querySelector('#selected_items_table tbody');

				let exists = Array.from(selectedTableBody.querySelectorAll('tr')).some(row => {
					return row.querySelector('input[name="item_id[]"]').value === id;
				});

				if (!exists) {
					let newRow = selectedTableBody.insertRow(0);

					// Action cell
					let actionCell = newRow.insertCell(0);
					actionCell.innerHTML = `<button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="${id}"><i class="bi-trash"></i></button>`;

					// ID
					let idCell = newRow.insertCell(1);
					idCell.innerHTML = id + `<input name="item_id[]" type="hidden" value="${id}">`;

					// SKU
					let skuCell = newRow.insertCell(2);
					skuCell.innerHTML = sku + `<input name="sku[]" type="hidden" value="${sku}">`;

					// Name
					let nameCell = newRow.insertCell(3);
					nameCell.textContent = name;

					// Unit
					let unitCell = newRow.insertCell(4);
					unitCell.textContent = unit;

					// Price display & hidden
					let priceCell = newRow.insertCell(5);
					priceCell.innerHTML = `<span class="display-price">${price.toFixed(2)}</span>
										<input type="hidden" name="orig_price[]" value="${price}">`;

					// VAT column
					let vatCell = newRow.insertCell(6);
					vatCell.className = 'vat-col';
					// vatCell.innerHTML = `<input type="number" name="vat_amount[]" class="vat-inclusive-price border-0 text-end" value="0" readonly style="width:80px; display:none;">`;
					vatCell.innerHTML = `
						<input type="hidden" name="vat_rate[]" class="vat-input" value="0">
						<input type="number" name="vat_inclusive_price[]" 
							class="vat-inclusive-price border-0 text-end"
							value="0" readonly style="width:80px; display:none;">
					`;

					// Net of VAT
					let novCell = newRow.insertCell(7);
					novCell.className = 'vat-col';
					novCell.innerHTML = `<span>${price.toFixed(2)}</span>`;

					// RIS
					let risCell = newRow.insertCell(8);
					risCell.innerHTML = ris_no + `<input name="ris_no[]" type="hidden" value="${ris_no}">`;

					// Purpose
					let purposeCell = newRow.insertCell(9);
					purposeCell.innerHTML = `<input name="po_item_purpose[]" type="text">`;

					// Remarks
					let remarksCell = newRow.insertCell(10);
					remarksCell.innerHTML = `<input name="po_item_remarks[]" type="text">`;

					// Quantity
					let qtyCell = newRow.insertCell(11);
					qtyCell.innerHTML = `<input name="quantity[]" class="text-end" type="number" step="1" value="${quantity}" min="1" onclick="this.select()" oninput="recalculateRow(this)">`;

					// Subtotal
					let subtotalCell = newRow.insertCell(12);
					subtotalCell.className = 'text-end';
					subtotalCell.innerHTML = `<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="${price.toFixed(2)*quantity}" readonly>`;

					// Extra hidden fields
					let extraCell = newRow.insertCell(13);
					extraCell.innerHTML = `<input type="hidden" name="item_purpose[]" value="${purpose}">
										<input type="hidden" name="item_remarks[]" value="${remarks}">`;

					applyVatToRow($(newRow));
					calculateGrandTotal();
					target.closest('tr').remove();
				} else {
					Swal.fire({ icon: 'warning', title: 'Item Already Added', text: 'This item is already in the selected list.', confirmButtonText: 'OK' });
				}
			}

			// Remove item
			if (target.classList.contains('remove-item-btn')) {
				target.closest('tr').remove();
				calculateGrandTotal();
			}
		});

		// Check at least one selected
		function checkSelectedItems() {
			const selectedItemsTable = document.querySelector('#selected_items_table tbody');
			if (!selectedItemsTable || selectedItemsTable.children.length === 0) {
				Swal.fire({ icon: 'warning', title: 'No Items Selected', text: 'Please select at least one item before saving.' });
				return false;
			}
			return true;
		}

		// Recalculate row when quantity changes
		function recalculateRow(input) {
			let row = $(input).closest('tr');
			let price = parseFloat(row.find('input[name="orig_price[]"]').val()) || 0;
			let qty = parseFloat($(input).val()) || 1;
			let isVatable = $('#supplier_id').find(':selected').data('vatable');
			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let displayPriceCell = row.find('.display-price');
			let subtotal = row.find('.subtotal');

			if (isVatable == 1) {
				let vatAmount = price * (VAT_RATE / 100);
				let netPrice = price - vatAmount;
				displayPriceCell.text(netPrice.toFixed(2));
				vatInput.val(VAT_RATE);
				vatIncl.val(vatAmount.toFixed(2)).show();
				subtotal.val((netPrice * qty).toFixed(2));
			} else {
				displayPriceCell.text(price.toFixed(2));
				vatInput.val(0).hide();
				vatIncl.val(0).hide();
				subtotal.val((price * qty).toFixed(2));
			}

			calculateGrandTotal();
		}

		// Apply VAT to row
		function applyVatToRow(row) {
			let isVatable = $('#supplier_id').find(':selected').data('vatable');
			let price = parseFloat(row.find('input[name="orig_price[]"]').val()) || 0;
			let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;
			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let displayPriceCell = row.find('.display-price');
			let subtotalInput = row.find('.subtotal');

			if (isVatable == 1) {
				let vatAmount = price * (VAT_RATE / 100);
				let netPrice = price - vatAmount;
				displayPriceCell.text(netPrice.toFixed(2));
				vatInput.val(VAT_RATE);
				vatIncl.val(vatAmount.toFixed(2)).show();
				subtotalInput.val((netPrice * qty).toFixed(2));
				$('.vat-col').show();
			} else {
				displayPriceCell.text(price.toFixed(2));
				vatInput.val(0).hide();
				vatIncl.val(0).hide();
				subtotalInput.val((price * qty).toFixed(2));
				$('.vat-col').hide();
			}
		}

		// Supplier change
		$('#supplier_id').on('change', function () {
			$('#selected_items_table tbody tr').each(function () {
				applyVatToRow($(this));
			});
			calculateGrandTotal();
		});

		// Calculate grand total
		function calculateGrandTotal() {
			let netTotal = 0;
			let vatTotal = 0;

			$('#selected_items_table tbody tr').each(function () {
				let qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 1;
				let netPrice = parseFloat($(this).find('.display-price').text()) || 0;
				let vatAmount = parseFloat($(this).find('.vat-inclusive-price').val()) || 0;

				netTotal += netPrice * qty;
				vatTotal += vatAmount * qty;
			});

			$('input[name="net_total"]').val(netTotal.toFixed(2));
			$('input[name="grand_total"]').val((netTotal + vatTotal).toFixed(2));
		}
	</script>
@endsection

{{-- @section('pagejs')
	<script>
		jQuery(document).ready(function(){
			// select Tags
			jQuery(".select-tags").select2({ tags: true });
		});

		// VAT rate
		const VAT_RATE = {{ env('VAT_RATE') }};

		// Handle AJAX search
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
							let resultsTableBody = $('#search_results_table tbody');
							resultsTableBody.html('');
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
											<td>
												<button type="button" class="btn btn-outline-primary add-item-btn"
													data-id="${item.id}"
													data-sku="${item.sku}"
													data-name="${item.name}"
													data-unit="${item.unit}"
													data-price="${item.price}"
													data-ris_no="${item.ris_no ?? 'N/A'}"
													data-quantity="${item.quantity ?? 1}"
													data-purpose="${item.purpose ?? ''}"
													data-remarks="${item.remarks ?? ''}">
													Add
												</button>
											</td>
										</tr>
									`);
								});
							} else {
								resultsTableBody.append('<tr><td class="text-center text-danger" colspan="100%">No items found</td></tr>');
							}
						},
						error: function(xhr) { console.log('Error:', xhr.responseText); }
					});
				} else {
					$('#search_results_table tbody').html('<tr><td class="text-center text-danger" colspan="100%">Search query is empty</td></tr>');
				}
			});
		});

		// Handle adding/removing items
		document.addEventListener('click', function(event) {
			let target = event.target.closest('button');
			if (!target) return;

			// Add item
			if (target.classList.contains('add-item-btn')) {
				let id = target.dataset.id;
				let sku = target.dataset.sku;
				let name = target.dataset.name;
				let unit = target.dataset.unit;
				let price = parseFloat(target.dataset.price) || 0;
				let ris_no = target.dataset.ris_no;
				let quantity = target.dataset.quantity ?? 1;
				let purpose = target.dataset.purpose;
				let remarks = target.dataset.remarks;

				let selectedTableBody = document.querySelector('#selected_items_table tbody');

				let exists = Array.from(selectedTableBody.querySelectorAll('tr')).some(row => {
					return row.querySelector('input[name="item_id[]"]').value === id;
				});

				if (!exists) {
					let newRow = selectedTableBody.insertRow(0);

					// Action cell
					let actionCell = newRow.insertCell(0);
					actionCell.innerHTML = `<button name="remove_selected[]" type="button" class="btn btn-outline-danger remove-item-btn" data-id="${id}"><i class="bi-trash"></i></button>`;

					// ID
					let idCell = newRow.insertCell(1);
					idCell.innerHTML = id + `<input name="item_id[]" type="hidden" value="${id}">`;

					// SKU
					let skuCell = newRow.insertCell(2);
					skuCell.innerHTML = sku + `<input name="sku[]" type="hidden" value="${sku}">`;

					// Name
					let nameCell = newRow.insertCell(3);
					nameCell.textContent = name;

					// Unit
					let unitCell = newRow.insertCell(4);
					unitCell.textContent = unit;

					// Price display & hidden
					let priceCell = newRow.insertCell(5);
					priceCell.innerHTML = `<span class="display-price">${price.toFixed(2)}</span>
										<input type="hidden" name="orig_price[]" value="${price}">`;

					// VAT column
					let vatCell = newRow.insertCell(6);
					vatCell.className = 'vat-col';
					// vatCell.innerHTML = `<input type="number" name="vat_amount[]" class="vat-inclusive-price border-0 text-end" value="0" readonly style="width:80px; display:none;">`;
					vatCell.innerHTML = `
						<input type="hidden" name="vat_rate[]" class="vat-input" value="0">
						<input type="number" name="vat_inclusive_price[]" 
							class="vat-inclusive-price border-0 text-end"
							value="0" readonly style="width:80px; display:none;">
					`;

					// Net of VAT
					let novCell = newRow.insertCell(7);
					novCell.className = 'vat-col';
					novCell.innerHTML = `<span>${price.toFixed(2)}</span>`;

					// RIS
					let risCell = newRow.insertCell(8);
					risCell.innerHTML = ris_no + `<input name="ris_no[]" type="hidden" value="${ris_no}">`;

					// Quantity
					let qtyCell = newRow.insertCell(9);
					qtyCell.innerHTML = `<input name="quantity[]" class="text-end" type="number" step="1" value="${quantity}" min="1" onclick="this.select()" oninput="recalculateRow(this)">`;

					// Subtotal
					let subtotalCell = newRow.insertCell(10);
					subtotalCell.className = 'text-end';
					subtotalCell.innerHTML = `<input class="subtotal text-end border-0" name="subtotal[]" type="number" value="${price.toFixed(2)*quantity}" readonly>`;

					// Extra hidden fields
					let extraCell = newRow.insertCell(11);
					extraCell.innerHTML = `<input type="hidden" name="item_purpose[]" value="${purpose}">
										<input type="hidden" name="item_remarks[]" value="${remarks}">`;

					applyVatToRow($(newRow));
					calculateGrandTotal();
					target.closest('tr').remove();
				} else {
					Swal.fire({ icon: 'warning', title: 'Item Already Added', text: 'This item is already in the selected list.', confirmButtonText: 'OK' });
				}
			}

			// Remove item
			if (target.classList.contains('remove-item-btn')) {
				target.closest('tr').remove();
				calculateGrandTotal();
			}
		});

		// Check at least one selected
		function checkSelectedItems() {
			const selectedItemsTable = document.querySelector('#selected_items_table tbody');
			if (!selectedItemsTable || selectedItemsTable.children.length === 0) {
				Swal.fire({ icon: 'warning', title: 'No Items Selected', text: 'Please select at least one item before saving.' });
				return false;
			}
			return true;
		}

		// Recalculate row when quantity changes
		function recalculateRow(input) {
			let row = $(input).closest('tr');
			let price = parseFloat(row.find('input[name="orig_price[]"]').val()) || 0;
			let qty = parseFloat($(input).val()) || 1;
			let isVatable = $('#supplier_id').find(':selected').data('vatable');
			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let displayPriceCell = row.find('.display-price');
			let subtotal = row.find('.subtotal');

			if (isVatable == 1) {
				let vatAmount = price * (VAT_RATE / 100);
				let netPrice = price - vatAmount;
				displayPriceCell.text(netPrice.toFixed(2));
				vatInput.val(VAT_RATE);
				vatIncl.val(vatAmount.toFixed(2)).show();
				subtotal.val((netPrice * qty).toFixed(2));
			} else {
				displayPriceCell.text(price.toFixed(2));
				vatInput.val(0).hide();
				vatIncl.val(0).hide();
				subtotal.val((price * qty).toFixed(2));
			}

			calculateGrandTotal();
		}

		// Apply VAT to row
		function applyVatToRow(row) {
			let isVatable = $('#supplier_id').find(':selected').data('vatable');
			let price = parseFloat(row.find('input[name="orig_price[]"]').val()) || 0;
			let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 1;
			let vatInput = row.find('.vat-input');
			let vatIncl = row.find('.vat-inclusive-price');
			let displayPriceCell = row.find('.display-price');
			let subtotalInput = row.find('.subtotal');

			if (isVatable == 1) {
				let vatAmount = price * (VAT_RATE / 100);
				let netPrice = price - vatAmount;
				displayPriceCell.text(netPrice.toFixed(2));
				vatInput.val(VAT_RATE);
				vatIncl.val(vatAmount.toFixed(2)).show();
				subtotalInput.val((netPrice * qty).toFixed(2));
				$('.vat-col').show();
			} else {
				displayPriceCell.text(price.toFixed(2));
				vatInput.val(0).hide();
				vatIncl.val(0).hide();
				subtotalInput.val((price * qty).toFixed(2));
				$('.vat-col').hide();
			}
		}

		// Supplier change
		$('#supplier_id').on('change', function () {
			$('#selected_items_table tbody tr').each(function () {
				applyVatToRow($(this));
			});
			calculateGrandTotal();
		});

		// Calculate grand total
		function calculateGrandTotal() {
			let netTotal = 0;
			let vatTotal = 0;

			$('#selected_items_table tbody tr').each(function () {
				let qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 1;
				let netPrice = parseFloat($(this).find('.display-price').text()) || 0;
				let vatAmount = parseFloat($(this).find('.vat-inclusive-price').val()) || 0;

				netTotal += netPrice * qty;
				vatTotal += vatAmount * qty;
			});

			$('input[name="net_total"]').val(netTotal.toFixed(2));
			$('input[name="grand_total"]').val((netTotal + vatTotal).toFixed(2));
		}
	</script>
@endsection --}}