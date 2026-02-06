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
            @include('theme.layouts.purchase_order-filters')
            --}}
            
        </div>
        
        <div class="row">
            <form class="d-flex align-items-center" id="searchForm" style="margin-bottom:10px;font-size: 12px !important;">
                <input type="hidden" name="is_search" id="is_search" value="1">
                <table width="100%" style="margin-bottom: 0px;">
                    <tr style="font-size:12px;font-weight:bold;">
                        <td>Start Date</td>
                        <td>End Date</td>
                        <td>Supplier</td>
                        <td>Status</td>
                        <td colspan="3">Search</td>
                    </tr>
                    <tr>
                        <td><input type="date" class="form-control" name="start_date" id="start_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['start_date']}}" @endif></td>
                        <td><input type="date" class="form-control" name="end_date" id="end_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['end_date']}}" @endif></td>
                        <td>
                            <select name="supplier" id="supplier" class="form-control" style="font-size:12px;">
                                <option value="">- All -</option>
                                @php $suppliers = \App\Models\Custom\Supplier::orderBy('name')->get(); @endphp
                                @forelse($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if(isset($_GET['supplier']) && $_GET['supplier']==$supplier->id) selected @endif>{{$supplier->name}}</option>
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
                         @if(RolePermission::has_permission(2,auth()->user()->role_id,1))
                            <td align="right"><a href="{{ route('receiving.purchase-orders.create') }}" class="btn text-white" style="font-size:14px; background-color: #0d6efd;">Create New Order</a></td>
                        @endif
                    </tr>
                    <tr><td><a href="{{route('receiving.purchase-orders.index')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                </table>
            </form>
            <div class="table-responsive-faker">

                <table id="authors_tbl" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>                            
                            <th>Ref #</th>
                            <th>Date Ordered</th>
                            <th>Supplier</th>
                            <th>Created</th>                            
                            {{-- <th>Updated</th>                             --}}
                            <th class="text-center">Status</th>
                            <th>Aging</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:12px !important;">
                        @forelse ($purchase_orders as $purchase_order)
                            <tr id="row{{$purchase_order->id}}">                                
                                <td valign="middle"><strong><a href="{{ route('receiving.purchase-orders.show', ['id' => $purchase_order->id]) }}">{{ $purchase_order->ref_no }}</a></strong></td>
                                <td valign="middle">{{ (new DateTime($purchase_order->date_ordered))->format('M d, Y') }}</td>
                                <td valign="middle" width="20%"><small>{!! \App\Models\Custom\PurchaseOrderHeader::suppliers_name($purchase_order->id) !!}</td>
                               
                                <td valign="middle">
                                    <strong>{{ User::getName($purchase_order->created_by) }}</strong><br>
                                    {{ Setting::date_for_listing($purchase_order->created_at) }}
                                </td>
                                {{-- <td valign="middle">
                                        <strong>{{ User::getName($purchase_order->updated_by) }}</strong><br>
                                        {{ Setting::date_for_listing($purchase_order->updated_at) }}
                                </td> --}}
                                <td class="text-center" valign="middle">
                                    <strong><small style="display: inline-block; width: 100px; text-align: center;font-size:12px;" class="rounded text-white {{ $purchase_order->status == 'SAVED' ? 'bg-secondary' : ($purchase_order->status == 'CANCELLED' ? 'bg-danger' : 'bg-success') }} p-1">{{ $purchase_order->status }}</small></strong><br>
                                    @if($purchase_order->status == 'POSTED')
                                        {{ Setting::date_for_listing($purchase_order->posted_at) }}
                                    @endif

                                    @if($purchase_order->status == 'CANCELLED')
                                       {{ Setting::date_for_listing($purchase_order->cancelled_at) }}
                                    @endif
                                </td>
                                <td>
                                    {{ App\Models\Custom\PurchaseOrderHeader::getTransactionAging($purchase_order->ref_no) }}
                                </td>

                                <td valign="middle">
                                    <a href="{{ route('receiving.purchase-orders.show', ['id' => $purchase_order->id]) }}" class="btn btn-light text-primary" title="View Transaction"><i class="bi-eye"></i></a>
                                    
                                    @if($purchase_order->status == 'SAVED' || $purchase_order->status == 'POSTED')
                                        @if(RolePermission::has_permission(2,auth()->user()->role_id,1) || RolePermission::has_permission(2,auth()->user()->role_id,2) || RolePermission::has_permission(2,auth()->user()->role_id,3))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light text-secondary shadow-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if($purchase_order->status == 'SAVED' || $purchase_order->status == 'POSTED')
                                                        @if(RolePermission::has_permission(2,auth()->user()->role_id,1))
                                                            <li>
                                                                <a href="{{ route('receiving.purchase-orders.edit', $purchase_order->id) }}" class="dropdown-item" title="Edit">
                                                                    <i class="uil-edit-alt"></i> Edit Details
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(RolePermission::has_permission(2,auth()->user()->role_id,3) && ($purchase_order->status != 'CANCELLED' && $purchase_order->status != 'POSTED'))
                                                            <li>
                                                                <a href="javascript:void(0)" class="dropdown-item" onclick="single_post({{ $purchase_order->id }})" title="Post Transaction">
                                                                    <i class="bi-send"></i> Post Transaction
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(RolePermission::has_permission(2,auth()->user()->role_id,2) && ($purchase_order->status != 'CANCELLED' && $purchase_order->status != 'POSTED'))
                                                            <li>
                                                                <a href="javascript:void(0)" class="dropdown-item" onclick="single_cancel({{ $purchase_order->id }})" title="Delete Transaction">
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
                        {{ $purchase_orders->appends($_GET)->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- MODALS --}}
    @include('theme.layouts.modals')
    
    
    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="purchase_orders" name="purchase_orders">
        <input type="text" id="status" name="status">
    </form>

@endsection

@section('pagejs')
	
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('receiving.purchase-orders.index') }}";
       
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
            post_form("{{ route('receiving.purchase-orders.single-restore') }}",'',id);
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
                    post_form("{{ route('receiving.purchase-orders.multiple-restore') }}", '', selected_items);
                });
            }
        }
        
        function single_post(id){
            $('.single-post').modal('show');
            $('.btn-post').on('click', function() {
                post_form("{{ route('receiving.purchase-orders.single-post') }}",'',id);
            });
        }

        function single_cancel(id){
            $('.single-cancel').modal('show');
            $('.btn-delete').on('click', function() {
                post_form("{{ route('receiving.purchase-orders.single-delete') }}",'',id);
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
                    post_form("{{ route('receiving.purchase-orders.multiple-delete') }}", '', selected_items);
                });
            }
        }
        
        function post_form(url,status,purchase_orders){
            $('#posting_form').attr('action',url);
            $('#purchase_orders').val(purchase_orders);
            $('#status').val(status);
            $('#posting_form').submit();
        }

    </script>


    {{-- <script>
        jQuery(document).ready(function() {
            jQuery('#authors_tbl').dataTable();
        });
    </script> --}}
@endsection