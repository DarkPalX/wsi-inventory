@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-6">
                <strong class="text-uppercase"><h3 style="margin-bottom: 0px;">{{ $page->name }}</h3></strong>

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
            
            {{-- FILTERS AMD ACTIONS 
            @include('theme.layouts.requisition-filters')
            --}}
            
        </div>
        
        <div class="row">
            <form class="d-flex align-items-center" id="searchForm" style="margin-bottom:10px;font-size: 12px !important;">
                <input type="hidden" name="is_search" id="is_search" value="1">
                <table width="100%" style="margin-bottom: 0px;">
                    <tr style="font-size:12px;font-weight:bold;">
                        <td>Start Date</td>
                        <td>End Date</td>
                        <td>Requester</td>
                        <td>Status</td>
                        <td colspan="3">Search</td>
                    </tr>
                    <tr>
                        <td><input type="date" class="form-control" name="start_date" id="start_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['start_date']}}" @endif></td>
                        <td><input type="date" class="form-control" name="end_date" id="end_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['end_date']}}" @endif></td>
                        <td>
                            <select name="requester" id="requester" class="form-control" style="font-size:12px;">
                                <option value="">- All -</option>
                                @php $requesters = \App\Models\User::orderBy('name')->get(); @endphp
                                @forelse($requesters as $requester)
                                    <option value="{{$requester->id}}" @if(isset($_GET['requester']) && $_GET['requester']==$requester->id) selected @endif>{{$requester->name}}</option>
                                @empty

                                @endforelse
                            </select>
                        </td>
                        <td>
                            <select name="status" id="status" class="form-control" style="font-size:12px;">
                                <option value="" selected>- All -</option>
                                <option value="SAVED" @if(isset($_GET['status']) && $_GET['status']=='SAVED') selected @endif>SAVED</option>
                                <option value="POSTED" @if(isset($_GET['status']) && $_GET['status']=='POSTED') selected @endif>POSTED</option>
                                <option value="CANCELLED" @if(isset($_GET['status']) && $_GET['status']=='CANCELLED') selected @endif>CANCELLED</option>
                            </select>
                        </td>
                        <td width="30%"><input name="search" type="search" id="search" class="form-control" placeholder="Search by Ref#, SKU, Item, Remarks"  @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif style="font-size:12px;"></td>
                        <td>
                            <input type="submit" class="btn text-light" value="Search" style="font-size:12px; background-color: #3d80e3;">
                        </td>
                         @if(RolePermission::has_permission(4,auth()->user()->role_id,1))
                            <td align="right"><a href="{{ route('issuance.requisitions.create') }}" class="btn text-white" style="font-size:14px; background-color: #0d6efd;">Create New Request</a></td>
                        @endif
                    </tr>
                    <tr><td><a href="{{route('issuance.requisitions.index')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                </table>
            </form>
            <div class="table-responsive-faker">

                <table id="authors_tbl" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>                            
                            <th>RIS #</th>
                            <th>Date Requested</th>
                            <th>Requested By</th>
                            {{-- <th>Type</th>
                            <th>Parts Needed</th>
                            <th>Assessment</th> --}}
                            {{-- <th>Purpose</th>    
                            <th>Remarks</th>     --}}
                            <th class="text-center">Request Status</th>
                            <th class="text-center">Issuance Status</th>
                            <th>Aging</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:12px !important;">
                        @forelse ($requisitions as $requisition)
                            <tr id="row{{$requisition->id}}">                                
                                <td valign="middle"><strong><a href="{{ route('issuance.requisitions.show', ['id' => $requisition->id]) }}">{{ $requisition->ref_no }}</a></strong></td>
                                <td valign="middle">{{ (new DateTime($requisition->date_requested))->format('M d, Y') }}</td>
                                <td valign="middle">
                                    <strong>{{ User::getName($requisition->requested_by) }}</strong><br>
                                    {{ Setting::date_for_listing($requisition->created_at) }}
                                </td>

                                {{-- <td valign="middle">{{ $requisition->requisition_type }}</td>
                                <td valign="middle">{{ $requisition->requisition_parts_needed }}</td>
                                <td valign="middle">{{ $requisition->requisition_assessment }}</td> --}}
                                {{-- <td valign="middle">{{ $requisition->purpose ?? '-' }}</td>
                                <td valign="middle">{{ $requisition->remarks ?? '-' }}</td> --}}
                                <td class="text-center" valign="middle">
                                    <strong><small style="display: inline-block; width: 100px; text-align: center;font-size:12px;" class="rounded text-white {{ $requisition->status == 'SAVED' ? 'bg-secondary' : ($requisition->status == 'CANCELLED' ? 'bg-danger' : 'bg-success') }} p-1">{{ $requisition->status }}</small></strong><br>
                                    @if($requisition->status == 'POSTED')
                                        {{ Setting::date_for_listing($requisition->posted_at) }}
                                    @endif

                                    @if($requisition->status == 'CANCELLED')
                                       {{ Setting::date_for_listing($requisition->cancelled_at) }}
                                    @endif
                                </td>
                                <td class="text-center" valign="middle">
                                    @if(App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no) && App\Models\Custom\IssuanceHeader::getIssuanceStatus($requisition->ref_no) > 0)
                                        <strong><small style="display: inline-block; width: 100px; text-align: center;font-size:12px;" class="rounded text-white bg-warning p-1">PARTIAL</small></strong><br>
                                    @elseif(App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no) && App\Models\Custom\IssuanceHeader::getIssuanceStatus($requisition->ref_no) == 0)
                                        <strong><small style="display: inline-block; width: 100px; text-align: center;font-size:12px;" class="rounded text-white bg-success p-1">COMPLETED</small></strong><br>
                                    @else
                                        @if($requisition->status == 'POSTED')
                                            <strong><small style="display: inline-block; width: 100px; text-align: center;font-size:12px;" class="rounded text-white bg-secondary p-1">PENDING</small></strong><br>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ App\Models\Custom\RequisitionHeader::getTransactionAging($requisition->ref_no) }}
                                </td>

                                <td valign="middle">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light text-primary shadow-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi-eye"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('issuance.requisitions.show', ['id' => $requisition->id]) }}" class="dropdown-item" title="View Requests">
                                                    <i class="bi-eye"></i> View Requests
                                                </a>
                                            </li>
                                            
                                            
                                            <li @if(!App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no)) hidden @endif>
                                                <a href="{{ route('issuance.requisitions.show-issuance',  $requisition->id) }}" class="dropdown-item" title="">
                                                    <i class="bi-eye"></i> View Issuance
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- @if($requisition->status == 'POSTED')
                                        @if(RolePermission::has_permission(3,auth()->user()->role_id,1))
                                            @if(App\Models\Custom\IssuanceHeader::hasIssuance($requisition->ref_no))
                                                <a href="javascript:void(0)" onclick="edit_issuance_post_form({{ $requisition->id }})" class="btn btn-primary" title="Update Issuance"><i class="uil-edit"></i></a>
                                                
                                                @if(App\Models\Custom\IssuanceHeader::hasEquipment($requisition->ref_no))
                                                    <a href="{{ route('issuance.transactions.print-barcode', ['ris_no' => $requisition->ref_no]) }}" class="btn btn-light text-primary shadow-0" title="Print Issued Equipment Barcodes">
                                                        <i class="uil-print"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="javascript:void(0)" onclick="create_issuance_post_form({{ $requisition->id }})" class="btn btn-primary" title="Create Issuance"><i class="uil-upload"></i></a>
                                            @endif
                                        @endif
                                    @endif --}}
                                    
                                    @if($requisition->status == 'SAVED')
                                        @if(RolePermission::has_permission(4,auth()->user()->role_id,1) || RolePermission::has_permission(4,auth()->user()->role_id,2) || RolePermission::has_permission(4,auth()->user()->role_id,3))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light text-secondary shadow-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if($requisition->status == 'SAVED')
                                                        @if(RolePermission::has_permission(4,auth()->user()->role_id,1))
                                                            <li>
                                                                <a href="{{ route('issuance.requisitions.edit', $requisition->id) }}" class="dropdown-item" title="Edit">
                                                                    <i class="uil-edit-alt"></i> Edit Details
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(RolePermission::has_permission(4,auth()->user()->role_id,3) && ($requisition->status != 'CANCELLED' && $requisition->status != 'POSTED'))
                                                            <li>
                                                                <a href="javascript:void(0)" class="dropdown-item" onclick="single_post({{ $requisition->id }})" title="Post Transaction">
                                                                    <i class="bi-send"></i> Post Transaction
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(RolePermission::has_permission(4,auth()->user()->role_id,2) && ($requisition->status != 'CANCELLED' && $requisition->status != 'POSTED'))
                                                            <li>
                                                                <a href="javascript:void(0)" class="dropdown-item" onclick="single_cancel({{ $requisition->id }})" title="Delete Transaction">
                                                                    <i class="fa-solid fa-cancel"></i> Cancel Transaction
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-danger p-5" colspan="100%">No item available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="row">
                    <div class="col-md-12">
                        {{ $requisitions->appends($_GET)->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- MODALS --}}
    @include('theme.layouts.modals')
    
    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="requisitions" name="requisitions">
        <input type="text" id="status" name="status">
    </form>
    
    <form action="{{ route('issuance.requisitions.create-issuance') }}" id="create_issuance_post_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="requisition_id" name="requisition_id">
    </form>
    
    <form action="{{ route('issuance.requisitions.edit-issuance') }}" id="edit_issuance_post_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="requisition_id_update" name="requisition_id">
    </form>

    

@endsection

@section('pagejs')
	
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('issuance.requisitions.index') }}";
       
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
        
        function single_restore(id){
            post_form("{{ route('issuance.requisitions.single-restore') }}",'',id);
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
                    post_form("{{ route('issuance.requisitions.multiple-restore') }}", '', selected_items);
                });
            }
        }
        
        function single_post(id){
            $('.single-post').modal('show');
            $('.btn-post').on('click', function() {
                post_form("{{ route('issuance.requisitions.single-post') }}",'',id);
            });
        }

        function single_cancel(id){
            $('.single-cancel').modal('show');
            $('.btn-delete').on('click', function() {
                post_form("{{ route('issuance.requisitions.single-delete') }}",'',id);
            });
        }

        function multiple_cancel() {
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
                $('.multiple-cancel').modal('show');
                $('.btn-delete-multiple').on('click', function() {
                    post_form("{{ route('issuance.requisitions.multiple-delete') }}", '', selected_items);
                });
            }
        }
        
        function post_form(url,status,requisitions){
            $('#posting_form').attr('action',url);
            $('#requisitions').val(requisitions);
            $('#status').val(status);
            $('#posting_form').submit();
        }
        
        function create_issuance_post_form(id){
            $('#requisition_id').val(id);
            $('#create_issuance_post_form').submit();
        }
        
        function edit_issuance_post_form(id){
            $('#requisition_id_update').val(id);
            $('#edit_issuance_post_form').submit();
        }

    </script>


    {{-- <script>
        jQuery(document).ready(function() {
            jQuery('#authors_tbl').dataTable();
        });
    </script> --}}
@endsection