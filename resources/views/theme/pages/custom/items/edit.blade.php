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
                        <li class="breadcrumb-item"><a href="{{ route('items.index') }}">{{ $page->name }}</a></li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">Item Properties</div>

                        <div class="card-body">

							<form method="post" action="{{ route('items.update', $item->id) }}" enctype="multipart/form-data">
                                @csrf
								@method('put')

								<div class="tab-content">
									<div class="tab-pane fade show active" id="main-tab" role="tabpanel" tabindex="0">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name', $item->name) }}" required>
												@error('name')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Category</label>
											<div class="col-sm-10">
												<select class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}" id="category_id" name="category_id" required>
													<option value="">-- SELECT CATEGORY --</option>
													@foreach($categories as $category)
														<option value="{{ $category->id }}" {{ old('category_id', $item->category->id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
													@endforeach
												</select>
												@error('category_id')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Package Type (UoM)</label>
											<div class="col-sm-10">
												<select id="type_id" name="type_id" class="select-tags form-select {{ $errors->has('type_id') ? 'is-invalid' : '' }}" aria-hidden="true" style="width:100%;" >
													<option value="">-- SELECT TYPE --</option>
													@foreach($types as $type)
														<option value="{{ $type->id }}" {{ old('type_id', $item->type->id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
													@endforeach
												</select>
												@error('type_id')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Price</label>
											<div class="col-sm-10">
												<input type="number" class="form-control bg-light" id="price" name="price" step="0.01" min="0" value="{{ old('price',  $item->price) }}" onclick="select()">
											</div>
										</div>	
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Minimum Stock Limit</label>
											<div class="col-sm-10">
												<input type="number" class="form-control bg-light" id="minimum_stock" name="minimum_stock" step="1" min="0" value="{{ old('minimum_stock',  $item->minimum_stock) }}" onclick="select()" oninput="if(this.value < 0) this.value = 0;">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">&nbsp;</label>
											<div class="col-sm-10">
												<input name="is_inventory" type="checkbox" value="0" {{ $item->is_inventory == 0 ? 'checked' : '' }}><label class="mx-2">Non-Inventory Item</label>
												{{-- <input name="is_inventory" type="radio" value="1" {{ $item->is_inventory == 1 ? 'checked' : '' }}><label class="mx-2">Inventory</label>						
												<input name="is_inventory" type="radio" value="0" {{ $item->is_inventory == 0 ? 'checked' : '' }}><label class="mx-2">Non-inventory</label>--}}
											</div>
										</div>
										<div class="form-group row" hidden>
											<label class="col-sm-2 col-form-label">Suppliers</label>
											<div class="col-sm-10">
												<select id="supplier_id" name="supplier_id[]" class="select-tags form-select" multiple aria-hidden="true" style="width:100%;">
													<option value="">-- SELECT SUPPLIER --</option>
													@foreach($suppliers as $supplier)
														<option value="{{ $supplier->id }}" {{ in_array($supplier->id, json_decode($item->supplier_id ?? '[]', true)) ? 'selected' : '' }}>
															{{ $supplier->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>	
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Location</label>
											<div class="col-sm-10">
												<input type="text" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" id="location" name="location" value="{{ old('location', $item->location) }}">
												@error('location')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
										<div id="cover_image_input" class="form-group row" @if(!is_null($item->image_cover)) style="display: none" @endif>
											<label class="col-sm-2 col-form-label">Item Photo</label>
											<div class="col-sm-10">
												<input id="image_cover" name="image_cover" class="input-file" type="file" class="file-loading" data-show-preview="false" accept=".png, .jpg">
												<small id="image_cover_error" class="text-danger" style="display: none;">Image size must not exceed {{ env('IMAGE_COVER_SIZE') }} MB.</small>
											</div>
										</div>
										
										<div id="cover_image_display" class="form-group row" @if(is_null($item->image_cover)) style="display: none" @endif>
											<label class="col-sm-2 col-form-label">Item Photo</label>
											<div class="col-sm-10">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">
															<i class="bi-image"></i>
														</span>
													</div>
													<input type="text" value="{{ basename($item->image_cover) }}" class="form-control" readonly>
													<div class="input-group-append">
														<button type="button" class="btn btn-outline-danger" onclick="remove_file('#cover_image_display', '#cover_image_input')">
															<i class="bi-trash"></i>
														</button>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-10">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="{{ route('items.index') }}" class="btn btn-light">Back</a>
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

			// Select Tags
			jQuery(".select-tags").select2({
				tags: true
			});
		});
	</script>

	{{-- FILE VALIDATION --}}
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			function validateFileSize(inputElementId, errorElementId, maxSizeMb) {
				const fileInput = document.getElementById(inputElementId);
				const fileError = document.getElementById(errorElementId);
				const maxSize = maxSizeMb * 1024 * 1024;

				fileInput.addEventListener('change', function () {
					const file = fileInput.files[0];
					if (file && file.size > maxSize) {
						fileError.style.display = 'block';
						fileInput.value = '';
					} else {
						fileError.style.display = 'none';
					}
				});
			}

			// Apply the validation function to both file inputs
			validateFileSize('image_cover', 'image_cover_error', {{ env('IMAGE_COVER_SIZE') }});
			validateFileSize('file_url', 'file_url_error', {{ env('FILE_URL_SIZE') }});
			validateFileSize('print_file_url', 'print_file_url_error', {{ env('PRINT_FILE_URL_SIZE') }});
		});
		
		function remove_file(remove_div, show_div){
			$(remove_div).hide();
			$(show_div).show();
		}
	</script>
@endsection