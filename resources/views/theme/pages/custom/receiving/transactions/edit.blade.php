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

								{{-- <div class="form-group row">
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										<select title="Printers/Suppliers are auto-generated" id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" required>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
													{{ $supplier->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div> --}}
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">P.O. #</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="Type to search P.O. #" value="{{ $transaction->po_number }}" list="po_number_list" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }" disabled readonly>
										<input type="text" id="po_number" name="po_number" value="{{ $transaction->po_number }}" hidden>
										{{-- <input type="text" id="old_po_number" name="old_po_number" value="{{ $transaction->po_number }}" hidden> --}}
										<datalist id="po_number_list"></datalist>
									</div>
								</div>
								{{-- <div class="form-group row">
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										<select title="Printers/Suppliers are auto-generated" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" disabled>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
													{{ $supplier->name }}
												</option>
											@endforeach
										</select>
										<select title="Printers/Suppliers are auto-generated" name="supplier_id[]" class="form-select" style="width:100%; display:none;" required>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
													{{ $supplier->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div> --}}
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										<select class="supplier_id form-select" style="width:100%;" required disabled>
											<option value=""></option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>{{ $supplier->name }}</option>
											@endforeach
										</select>
										<select name="supplier_id[]" class="supplier_id form-select" style="width:100%;" required hidden>
											<option value=""></option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true)) ? 'selected' : '' }}>{{ $supplier->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">S.I. #</label>
									<div class="col-sm-10">
										<input type="text" id="si_number" name="si_number"  class="form-control" placeholder="Type to search S.I. #" value="{{ $transaction->si_number }}" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Date Received</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_received" name="date_received" value="{{ $transaction->date_received }}" required>
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

								<div class="divider text-uppercase divider-center"><small>Item Details</small></div>
								
								<div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover" id="purchased_items_table">
											<thead>
												<tr>
													<th width="5%">ID</th>
													<th width="10%">SKU</th>
													<th width="35%">Item</th>
													<th width="10%">Unit</th>
													<th width="10%">Price</th>
													<th width="10%" class="vat-col" {{ $is_vatable ? '' : 'hidden' }}><input type="checkbox" id="select_all_vat" hidden> VAT({{ env('VAT_RATE') }}%)</th>
													{{-- <th width="10%"><input type="checkbox" id="select_all_vat" hidden> VAT({{ env('VAT_RATE') }}%)</th> --}}
													<th width="10%" class="text-end">Qty Ordered</th>
													<th width="10%" class="text-end">Qty Received</th>
												</tr>
											</thead>
											<tbody>
												@foreach($receiving_details as $receiving_detail)
													<tr>
														<td>{{ $receiving_detail->item_id }} <input name="item_id[]" type="text" value="{{ $receiving_detail->item_id }}" hidden></td>
														<td>{{ $receiving_detail->sku }} <input name="sku[]" type="text" value="{{ $receiving_detail->sku }}" hidden></td>
														<td>{{ $receiving_detail->item()->withTrashed()->first()->name }}</td>
														<td>{{ optional(optional($receiving_detail->item)->type)->name ?? 'N/A' }}</td>
														<td><input name="price[]" type="number" class="text-end" value="{{ $receiving_detail->price }}" step="1" min="1" onclick="this.select()" oninput="this.value = this.value < 1 ? 0 : this.value" @if($transaction->status == "POSTED") readonly @endif readonly></td>
														<td {{ $receiving_detail->vat > 0 ? '' : 'hidden' }}>
															<div class="vat-container" style="display: inline-flex; align-items: center; gap: 4px;">
																<input type="checkbox" class="vat-check" {{ $receiving_detail->vat > 0 ? 'checked' : '' }} hidden>

																<!-- Stores vat % (12%) -->
																<input class="vat-input" name="vat[]" type="number" value="{{ $receiving_detail->vat }}" hidden>

																<!-- NEW: price including VAT -->
																<input class="new-price-input border-0 text-end"
																	name="vat_inclusive_price[]"
																	type="number"
																	value="{{ $receiving_detail->vat_inclusive_price }}"
																	readonly
																	style="width:80px;">
															</div>
														</td>
														{{-- <td><input type="checkbox" class="vat-check" {{ $receiving_detail->vat > 0 ? 'checked' : '' }}><input class="vat-input" name="vat[]" type="number" value="{{ $receiving_detail->vat }}" hidden></td> --}}
														<td><input name="order[]" class="border-0 text-end" type="number" value="{{ $receiving_detail->order }}" readonly></td>
														<td><input name="quantity[]" class="text-end" type="number" onclick="select()" step="1" value="{{ $receiving_detail->quantity }}" min="0" oninput="this.value = this.value < 0 ? 0 : (this.value > {{ $receiving_detail->order }} ? {{ $receiving_detail->order }} : this.value);"></td>
													</tr>
												@endforeach
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
        $(document).ready(function () {
            $('#po_number').on('input', function () {
                const query = $(this).val().trim();

                if (query.length > 0) { // Fetch data only if input is longer than 2 characters
                    $.ajax({
						url: '{{ route("receiving.transactions.search-po-number") }}',
                        method: 'GET',
                        data: { q: query },
                        success: function (data) {

							const options = data.results;
                            const datalist = $('#po_number_list');

                            datalist.empty(); // Clear existing options

                            options.forEach(item => {
                                datalist.append(`<option value="${item.ref_no}">`);
                            });

							if (options.length > 0) {
								let last = options[options.length - 1];

								let supplierIDs = JSON.parse(last.supplier_id);

								let select = $('.supplier_id');

								select.val(null).trigger('change');

								// mark matching suppliers as selected
								supplierIDs.forEach(id => {
									select.find(`option[value="${id}"]`).prop('selected', true);
								});

								// refresh Select2
								select.trigger('change');
							}

							//Populate Selected Purchased Items on Table
							if (options != []) {
								$.ajax({
									url: '{{ route("receiving.transactions.search-purchased-item") }}',
									method: 'GET',
									data: { q: query },
									success: function(data) {
										console.log(data);
										let resultsTableBody = $('#purchased_items_table tbody');
										resultsTableBody.html(''); // Clear the table

										if (data.results.length) {
											data.results.forEach(item => {
												resultsTableBody.append(`
													<tr>
														<td>${item.item_id} <input name="item_id[]" type="text" value="${item.item_id}" hidden></td>
														<td>${item.sku} <input name="sku[]" type="text" value="${item.sku}" hidden></td>
														<td>${item.item_name}</td>
														<td>${item.unit}</td>
														<td><input name="price[]" type="number" class="text-end" value="${(parseFloat(item.price) || 0).toFixed(2)}" step="1" min="1" onclick="this.select()" oninput="this.value = this.value < 1 ? 0 : this.value"></td>
														<td class="vat-col">
															<div class="vat-container" style="display: inline-flex; align-items: center; gap: 4px;">
																<input type="checkbox" class="vat-check" hidden>

																<!-- Stores vat % (12%) -->
																<input class="vat-input" name="vat[]" type="number" value="${item.vat}" hidden>

																<!-- NEW: price including VAT -->
																<input class="new-price-input border-0 text-end"
																	name="vat_inclusive_price[]"
																	type="number"
																	value="${item.vat_inclusive_price}"
																	readonly
																	style="width:80px; ${item.vat_inclusive_price > 0 ? '':'display:none;'}">
															</div>
														</td>
														<td><input class="border-0 text-end" name="order[]" type="number" value="${item.remaining}" readonly></td>
														<td><input name="quantity[]" class="text-end" type="number" onclick="select()" step="1" value="${item.remaining}" min="0" oninput="this.value = this.value < 0 ? 0 : (this.value > ${item.remaining} ? ${item.remaining} : this.value);"></td>
													</tr>
												`);
												
												if(item.vat_inclusive_price > 0){
													$('.vat-col').show()
												}
												else{
													$('.vat-col').hide()
												}

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
								$('#purchased_items_table tbody').html('<tr><td class="text-center text-danger" colspan="100%">Search query is empty</td></tr>');
							}
							//

                        },
                        error: function () {
                            console.error('Error fetching data.');
                        }
                    });
                }
            });

        });


		// FOR VAT
		const VAT_RATE = {{ env('VAT_RATE') }};

		// VAT checkbox logic
		$(document).on('change', '.vat-check', function () {
			let row = $(this).closest('tr');
			let price = parseFloat(row.find('input[name="price[]"]').val()) || 0;

			let vatInput = row.find('.vat-input');             // hidden vat[]
			let newPriceInput = row.find('.new-price-input');  // VAT price textbox

			if (this.checked) {
				vatInput.val(VAT_RATE); // store 12%

				let newPrice = price * (1 + VAT_RATE / 100);
				newPriceInput.val(newPrice.toFixed(2));

				newPriceInput.show(); // show input
			} else {
				vatInput.val(0);
				newPriceInput.val(0);
				newPriceInput.hide(); // hide input
			}
		});

		// VAT select all box
		$('#select_all_vat').on('change', function () {
			let checked = this.checked;
			$('.vat-check').each(function () {
				$(this).prop('checked', checked).trigger('change');
			});
		});
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
        $(document).ready(function () {
            $('#po_number').on('input', function () {
                const query = $(this).val().trim();

                if (query.length > 0) { // Fetch data only if input is longer than 2 characters
                    $.ajax({
						url: '{{ route("receiving.transactions.search-po-number") }}',
                        method: 'GET',
                        data: { q: query },
                        success: function (data) {

							const options = data.results;
                            const datalist = $('#po_number_list');

                            datalist.empty(); // Clear existing options

                            options.forEach(item => {
                                datalist.append(`<option value="${item.ref_no}">`);
                            });

							//Populate Selected Purchased Items on Table
							if (options != []) {
								$.ajax({
									url: '{{ route("receiving.transactions.search-purchased-item") }}',
									method: 'GET',
									data: { q: query },
									success: function(data) {
										console.log(data);
										let resultsTableBody = $('#purchased_items_table tbody');
										resultsTableBody.html(''); // Clear the table

										if (data.results.length) {
											data.results.forEach(item => {
												resultsTableBody.append(`
													<tr>
														<td>${item.item_id} <input name="item_id[]" type="text" value="${item.item_id}" hidden></td>
														<td>${item.sku} <input name="sku[]" type="text" value="${item.sku}" hidden></td>
														<td>${item.item_name}</td>
														<td>${item.unit}</td>
														<td><input name="price[]" type="number" value="${(parseFloat(item.price) || 0).toFixed(2)}" step="1" min="1" onclick="this.select()" oninput="this.value = this.value < 1 ? 0 : this.value"></td>
														<td>
															<div class="vat-container" style="display: inline-flex; align-items: center; gap: 4px;">
																<input type="checkbox" class="vat-check">

																<!-- Stores vat % (12%) -->
																<input class="vat-input" name="vat[]" type="number" value="0" hidden>

																<!-- NEW: price including VAT -->
																<input class="new-price-input border-0 text-end"
																	name="vat_inclusive_price[]"
																	type="number"
																	value="0"
																	readonly
																	style="width:80px; display:none;">
															</div>
														</td>
														<td><input class="border-0 text-end" name="order[]" type="number" value="${item.remaining}" readonly></td>
														<td><input name="quantity[]" class="text-end" type="number" onclick="select()" step="1" value="${item.remaining}" min="0" oninput="this.value = this.value < 0 ? 0 : (this.value > ${item.remaining} ? ${item.remaining} : this.value);"></td>
													</tr>
												`);

												// <td><input type="checkbox" class="vat-check"><input class="vat-input" name="vat[]" type="number" value="0" hidden></td>

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
								$('#purchased_items_table tbody').html('<tr><td class="text-center text-danger" colspan="100%">Search query is empty</td></tr>');
							}
							//

                        },
                        error: function () {
                            console.error('Error fetching data.');
                        }
                    });
                }
            });

        });


		// FOR VAT
		const VAT_RATE = {{ env('VAT_RATE') }};

		// VAT checkbox logic
		$(document).on('change', '.vat-check', function () {
			let row = $(this).closest('tr');
			let price = parseFloat(row.find('input[name="price[]"]').val()) || 0;

			let vatInput = row.find('.vat-input');             // hidden vat[]
			let newPriceInput = row.find('.new-price-input');  // VAT price textbox

			if (this.checked) {
				vatInput.val(VAT_RATE); // store 12%

				let newPrice = price * (1 + VAT_RATE / 100);
				newPriceInput.val(newPrice.toFixed(2));

				newPriceInput.show(); // show input
			} else {
				vatInput.val(0);
				newPriceInput.val(0);
				newPriceInput.hide(); // hide input
			}
		});

		// VAT select all box
		$('#select_all_vat').on('change', function () {
			let checked = this.checked;
			$('.vat-check').each(function () {
				$(this).prop('checked', checked).trigger('change');
			});
		});
    </script>
	
@endsection --}}