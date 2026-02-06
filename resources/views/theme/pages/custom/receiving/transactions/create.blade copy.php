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
                        <li class="breadcrumb-item">Create</li>
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
                            
							<form method="post" action="{{ route('receiving.transactions.store') }}" enctype="multipart/form-data" onsubmit="return checkSelectedItems();">
                                @csrf
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Suppliers</label>
									<div class="col-sm-10">
										<select id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;" required>
											<option value="">-- SELECT SUPPLIER --</option>
											@foreach($suppliers as $supplier)
												<option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">P.O. #</label>
									<div class="col-sm-10">
										<input type="text" id="po_number" name="po_number" class="form-control" autocomplete="off" placeholder="Type to search P.O. #" list="po_number_list" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
										<datalist id="po_number_list"></datalist>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">S.I. #</label>
									<div class="col-sm-10">
										<input type="text" id="si_number" name="si_number" class="form-control" autocomplete="off" placeholder="Type S.I. #" onkeypress="if(event.key === 'Enter') { event.preventDefault(); }">
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Date Received</label>
									<div class="col-sm-10">
										<input type="date" class="form-control" id="date_received" name="date_received" value="<?= date('Y-m-d'); ?>" required>
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
										<table class="table table-hover" id="purchased_items_table">
											<thead>
												<tr>
													<th width="5%">ID</th>
													<th width="10%">SKU</th>
													<th width="45%">Item</th>
													<th width="10%">Unit</th>
													<th width="10%">Price</th>
													<th width="10%" class="text-end">Qty Ordered</th>
													<th width="10%" class="text-end">Qty Received</th>
												</tr>
											</thead>
											<tbody>
												<!-- Selected items will be appended here -->
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
														<td><input class="border-0 text-end" name="order[]" type="number" value="${item.remaining}" readonly></td>
														<td><input name="quantity[]" class="text-end" type="number" onclick="select()" step="1" value="${item.remaining}" min="0" oninput="this.value = this.value < 0 ? 0 : (this.value > ${item.remaining} ? ${item.remaining} : this.value);"></td>
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
    </script>
	
@endsection