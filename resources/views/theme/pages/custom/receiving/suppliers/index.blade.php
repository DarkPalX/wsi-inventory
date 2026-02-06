@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-6">
                <strong class="text-uppercase">{{ $page->name }}</strong>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">{{ $page->name }}</li>
                        <li class="breadcrumb-item">Manage</li>
                    </ol>
                </nav>
                
            </div>
            
        </div>

        <div class="row mt-4 mb-3">
            
            {{-- FILTERS AMD ACTIONS --}}
            @include('theme.layouts.filters')

            <div class="col-md-6 d-flex align-items-center justify-content-end">
                <form class="d-flex align-items-center" id="searchForm" style="margin-bottom: 0; margin-right: -2%;">
                    <input name="search" type="search" id="search" class="form-control" placeholder="Search by Name" value="{{ $filter->search }}" style="width: auto;">
                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                </form>

                <a data-bs-toggle="modal" data-bs-target=".create-form-modal" class="btn btn-primary">Create New Supplier</a>
                {{-- <a href="{{ route('receiving.suppliers.create') }}" class="btn btn-primary">Create New Supplier</a> --}}
            </div>
            
        </div>
        
        <div class="row">

            <div class="table-responsive-faker" style="background-color: aliceblue;">
                <table id="authors_tbl" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>
                            <th width="2%">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Person in-charge</th>
                            <th>Email</th>
                            <th width="15%">Cellphone #</th>
                            <th width="15%">Telephone #</th>
                            <th>Vatable ({{ env('VAT_RATE') }}%)</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr id="row{{$supplier->id}}" @if($supplier->trashed()) class="table-danger" @endif>
                                <td>
                                    <input type="checkbox" class="@if($supplier->trashed()) item-trashed @else select-item @endif" id="cb{{ $supplier->id }}" @if($supplier->trashed()) disabled @endif>
                                    <label class="custom-control-label" for="cb{{ $supplier->id }}"></label>
                                </td>
                                <td><strong>{{ $supplier->name }}</strong></td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->person_in_charge }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->cellphone_no }}</td>
                                <td>{{ $supplier->telephone_no }}</td>
                                <td>{{ $supplier->is_vatable == 1 ? "Yes" : "No"  }}</td>
                                <td class="flex justify-center items-center">
                                    @if($supplier->trashed())
                                        <a href="javascript:void(0)" class="btn btn-transparent text-primary">&nbsp;</a>
                                        {{-- <a href="javascript:void(0)" class="btn btn-light text-primary" onclick="single_restore({{ $supplier->id }})"><i class="fa-solid fa-undo-alt"></i></a> --}}
                                    @else
                                        <a data-bs-toggle="modal" data-bs-target="#edit_supplier{{ $supplier->id }}" class="btn btn-light text-warning"><i class="uil-edit-alt"></i></a>
                                        {{-- <a href="{{ route('receiving.suppliers.edit', $supplier->id) }}" class="btn btn-light text-warning"><i class="uil-edit-alt"></i></a> --}}
                                        <a href="javascript:void(0)" class="btn btn-light text-danger" onclick="single_delete({{ $supplier->id }})"><i class="uil-trash-alt"></i></a>
                                    @endif
                                </td>
                            </tr>

                            {{-- EDIT SUPPLIER MODAL --}}
                            <div id="edit_supplier{{ $supplier->id }}" class="modal fade text-start" tabindex="-1" role="dialog" aria-labelledby="centerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="centerModalLabel">Supplier's Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('receiving.suppliers.update', $supplier->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $supplier->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Address</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="address" name="address" value="{{ $supplier->address }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Person in-charge</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="person_in_charge" name="person_in_charge" value="{{ $supplier->person_in_charge }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Cellphone #</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" class="form-control" id="cellphone_no" name="cellphone_no" step="1" value="{{ $supplier->cellphone_no }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Telephone #</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" class="form-control" id="telephone_no" name="telephone_no" value="{{ $supplier->telephone_no }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $supplier->email }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">TIN #</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="tin_no" name="tin_no" value="{{ $supplier->tin_no }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Check #</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="check_no" name="check_no" value="{{ $supplier->check_no }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Bank Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $supplier->bank_name }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Account #</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="bank_account_no" name="bank_account_no" value="{{ $supplier->bank_account_no }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">&nbsp;</label>
                                                    <div class="col-sm-10">
                                                        <input name="is_vatable" type="checkbox" {{ $supplier->is_vatable == 1 ? 'checked' : '' }}><label class="mx-2">Vatable</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <a data-bs-dismiss="modal" class="btn btn-light">Cancel</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td class="text-center text-danger p-5" colspan="100%">No item available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="row">
                    <div class="col-md-12">
                        {{ $suppliers->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- MODALS --}}
    @include('theme.layouts.modals')

    <div class="modal fade text-start create-form-modal" tabindex="-1" role="dialog" aria-labelledby="centerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="centerModalLabel">Create New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('receiving.suppliers.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Person in-charge</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="person_in_charge" name="person_in_charge">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Cellphone #</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="cellphone_no" name="cellphone_no" step="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Telephone #</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="telephone_no" name="telephone_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">TIN #</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tin_no" name="tin_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Check #</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="check_no" name="check_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Bank Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bank_name" name="bank_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Account #</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bank_account_no" name="bank_account_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <input name="is_vatable" type="checkbox"><label class="mx-2">Vatable</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a data-bs-dismiss="modal" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="suppliers" name="suppliers">
        <input type="text" id="status" name="status">
    </form>

@endsection

@section('pagejs')
	
    <!-- jQuery -->
    {{-- <script src="{{ asset('theme/js/jquery-3.6.0.min.js') }}"></script> --}}

    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('receiving.suppliers.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        function single_delete(id){
            $('.single-delete').modal('show');
            $('.btn-delete').on('click', function() {
                post_form("{{ route('receiving.suppliers.single-delete') }}",'',id);
            });
        }

        function multiple_delete() {
            var counter = 0;
            var selected_items = '';

            $(".select-item:checked").each(function() {
                counter++;
                var fid = $(this).attr('id');
                
                if (fid !== undefined) {
                    selected_items += fid.substring(2) + '|';
                }
            });

            if (counter < 1) {
                $('.prompt-no-selected').modal('show');
                return false;
            } else {
                $('.multiple-delete').modal('show');
                $('.btn-delete-multiple').on('click', function() {
                    post_form("{{ route('receiving.suppliers.multiple-delete') }}", '', selected_items);
                });
            }
        }
        
        function single_restore(id){
            post_form("{{ route('receiving.suppliers.single-restore') }}",'',id);
        }

        function multiple_restore() {
            var counter = 0;
            var selected_items = '';

            $(".select-item:checked").each(function() {
                counter++;
                var fid = $(this).attr('id');
                
                if (fid !== undefined) {
                    selected_items += fid.substring(2) + '|';
                }
            });

            if (counter < 1) {
                $('.prompt-no-selected').modal('show');
                return false;
            } else {
                $('.multiple-restore').modal('show');
                $('.btn-restore-multiple').on('click', function() {
                    post_form("{{ route('receiving.suppliers.multiple-restore') }}", '', selected_items);
                });
            }
        }
        
        function post_form(url,status,suppliers){
            $('#posting_form').attr('action',url);
            $('#suppliers').val(suppliers);
            $('#status').val(status);
            $('#posting_form').submit();
        }
    </script>
    
    <script>
        document.querySelectorAll('.dropdown-menu').forEach(function (dropdown) {
            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });
    </script>

    {{-- <script>
        jQuery(document).ready(function() {
            jQuery('#authors_tbl').dataTable();
        });
    </script> --}}
@endsection