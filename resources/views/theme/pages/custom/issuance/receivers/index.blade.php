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

                <a data-bs-toggle="modal" data-bs-target=".create-form-modal" class="btn btn-primary">Create New Receiver</a>
                {{-- <a href="{{ route('issuance.receivers.create') }}" class="btn btn-primary">Create New Receiver</a> --}}
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
                            <th>Contact</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($receivers as $receiver)
                            <tr id="row{{$receiver->id}}" @if($receiver->trashed()) class="table-danger" @endif>
                                <td>
                                    <input type="checkbox" class="@if($receiver->trashed()) item-trashed @else select-item @endif" id="cb{{ $receiver->id }}" @if($receiver->trashed()) disabled @endif>
                                    <label class="custom-control-label" for="cb{{ $receiver->id }}"></label>
                                </td>
                                <td><strong>{{ $receiver->name }}</strong></td>
                                <td>{{ $receiver->address }}</td>
                                <td>{{ $receiver->contact }}</td>
                                <td class="flex justify-center items-center">
                                    @if($receiver->trashed())
                                        <a href="javascript:void(0)" class="btn btn-light text-primary" onclick="single_restore({{ $receiver->id }})"><i class="fa-solid fa-undo-alt"></i></a>
                                    @else
                                        <a data-bs-toggle="modal" data-bs-target="#edit_receiver{{ $receiver->id }}" class="btn btn-light text-warning"><i class="uil-edit-alt"></i></a>
                                        {{-- <a href="{{ route('issuance.receivers.edit', $receiver->id) }}" class="btn btn-light text-warning"><i class="uil-edit-alt"></i></a> --}}
                                        <a href="javascript:void(0)" class="btn btn-light text-danger" onclick="single_delete({{ $receiver->id }})"><i class="uil-trash-alt"></i></a>
                                    @endif
                                </td>
                            </tr>

                            {{-- EDIT RECEIVER MODAL --}}
                            <div id="edit_receiver{{ $receiver->id }}" class="modal fade text-start" tabindex="-1" role="dialog" aria-labelledby="centerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="centerModalLabel">Publisher's Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('issuance.receivers.update', $receiver->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $receiver->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Address</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="address" name="address" value="{{ $receiver->address }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Contact</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" class="form-control" id="contact" name="contact" value="{{ $receiver->contact }}">
                                                    </div>
                                                </div>
                
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <a href="{{ route('issuance.receivers.index') }}" class="btn btn-light">Back</a>
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
                        {{ $receivers->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                    <form method="post" action="{{ route('issuance.receivers.store') }}">
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
                            <label for="name" class="col-sm-2 col-form-label">Contact</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="contact" name="contact">
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
        <input type="text" id="receivers" name="receivers">
        <input type="text" id="status" name="status">
    </form>

@endsection

@section('pagejs')
	
    <!-- jQuery -->
    {{-- <script src="{{ asset('theme/js/jquery-3.6.0.min.js') }}"></script> --}}

    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('issuance.receivers.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        function single_delete(id){
            $('.single-delete').modal('show');
            $('.btn-delete').on('click', function() {
                post_form("{{ route('issuance.receivers.single-delete') }}",'',id);
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
                    post_form("{{ route('issuance.receivers.multiple-delete') }}", '', selected_items);
                });
            }
        }
        
        function single_restore(id){
            post_form("{{ route('issuance.receivers.single-restore') }}",'',id);
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
                    post_form("{{ route('issuance.receivers.multiple-restore') }}", '', selected_items);
                });
            }
        }
        
        function post_form(url,status,receivers){
            $('#posting_form').attr('action',url);
            $('#receivers').val(receivers);
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