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
        
        <div class="row mt-5">
            <div>
                <form class="d-flex align-items-center" id="searchForm" style="margin-bottom:10px;font-size: 12px !important;">
                    <input type="hidden" name="is_search" id="is_search" value="1">
                    <table width="100%" style="margin-bottom: 0px;">
                        <tr style="font-size:12px;font-weight:bold;">
                            {{-- <td>Start Date</td>
                            <td>End Date</td> --}}
                            <td>Category</td>
                            <td colspan="3">Search</td>
                        </tr>
                        <tr>
                            <td>
                                <select name="category" id="category" class="form-control" style="font-size:12px;">
                                    <option value="">- All -</option>
                                    @php $categories = \App\Models\Custom\ItemCategory::orderBy('name')->get(); @endphp
                                    @forelse($categories as $category)
                                        <option value="{{$category->id}}" @if(isset($_GET['category']) && $_GET['category']==$category->id) selected @endif>{{$category->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </td>
                            <td width="30%"><input name="search" type="search" id="search" class="form-control" placeholder="Search by SKU, Title, Subtitle, ISBN"  @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif style="font-size:12px;"></td>
                            <td>
                                <input type="submit" class="btn text-light" value="Search" style="font-size:12px; background-color: #3d80e3;">                                  
                            </td>
                            @if(RolePermission::has_permission(2,auth()->user()->role_id,1))
                               <td align="right"><a href="{{ route('items.create') }}" class="btn text-white" style="font-size:14px; background-color: #0d6efd;">Create New Item</a></td>
                           @endif
                        </tr>
                        <tr><td><a href="{{route('items.index')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                    </table>
                </form>
            </div>

            <div class="table-responsive-faker">

                <table class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>
                            {{-- <th width="2%">
                                <input type="checkbox" id="select-all">
                            </th> --}}
                            <th>SKU</th>
                            <th style="width: 5%;"></th>
                            <th width="20%">Item</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Inventory</th>
                            <th>Minimum Stock</th>
                            @if(RolePermission::has_permission(1,auth()->user()->role_id,1) || RolePermission::has_permission(1,auth()->user()->role_id,2))
                                <th width="10%">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody style="font-size:12px !important;">
                        @forelse ($items as $item)
                            @php $item_info = \App\Models\Custom\Item::withTrashed()->find($item->iid);@endphp
                            
                            <tr id="row{{$item_info->id}}" @if($item_info->Inventory < $item_info->minimum_stock) title="Inventory is below its limit"  data-row-index="0" @else  data-row-index="1" @endif>
                            {{-- <tr id="row{{$item_info->id}}" @if($item_info->Inventory < $item_info->minimum_stock) class="table-warning" title="Inventory is below its limit"  data-row-index="0" @else  data-row-index="1" @endif> --}}
                                {{-- <td onclick="event.stopPropagation();">
                                    <input type="checkbox" class="@if($item_info->deleted_at != null) item-trashed @else select-item @endif" id="cb{{ $item_info->id }}" @if($item_info->deleted_at != null) disabled @endif>
                                    <label class="custom-control-label" for="cb{{ $item_info->id }}"></label>
                                </td> --}}
                                <td onclick="event.stopPropagation();">{{ $item_info->sku }}<br></td>
                                <td><img height="54" width="54" src="{{ env('APP_URL') }}/{{$item_info->image_cover}}" onerror="this.src = '{{ env('APP_URL') }}/images/company-icon.ico';"/></td>
                                <td>{{ $item_info->name }}</td>
                                <td>{{ $item_info->category->name }}</td>
                                <td>{{ $item_info->type->name }}</td>
                                <td>{{ $item_info->location ?? '(Not Specified)'}}</td>
                                <td>₱ {{ number_format($item_info->price, 2) }}</td>
                                <td onclick="event.stopPropagation();">
                                    @if($item_info->is_inventory) 
                                        <a href="{{ route('items.stock-card', $item_info->id) }}" @if($item_info->Inventory > 0 ) class="text-dark" @endif><strong>{{ $item_info->Inventory }}</strong></a> 
                                    @else 
                                        {{-- <span class="badge bg-secondary">Non-inventory</span> --}}
                                    @endif
                                </td>
                                <td>
                                    @if($item_info->is_inventory) 
                                       {{ $item_info->minimum_stock }}
                                    @else 
                                        {{-- {{ $item_info->minimum_stock }} --}}
                                    @endif
                                </td>
                                <td class="flex justify-center items-center" onclick="event.stopPropagation();">
                                    @if($item_info->deleted_at != null)
                                        {{-- <a href="javascript:void(0)" class="btn text-primary" onclick="single_restore({{ $item_info->id }})"><i class="fa-solid fa-undo-alt"></i></a> --}}
                                    @else
                                         @if(RolePermission::has_permission(1,auth()->user()->role_id,1))
                                            <a href="{{ route('items.edit', $item_info->id) }}" class="btn btn-light text-warning"><i class="uil-edit-alt"></i></a>
                                        @endif
                                        @if(RolePermission::has_permission(1,auth()->user()->role_id,2))
                                            <a href="javascript:void(0)" class="btn btn-light text-danger" onclick="single_delete({{ $item_info->id }})"><i class="uil-trash-alt"></i></a>
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
                        {{ $items->appends($_GET)->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- SHOW BOOK --}}
    
    <div class="modal fade show-item mt-5" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content bg-transparent border-0 shadow-none" style="width:80%">
                <div class="flip-card text-center shadow" style="height: 584px;">
                    <div class="flip-card-front dark h-100" id="item_image" >
                        <div class="flip-card-inner h-100 d-flex flex-column justify-content-between">
                            <div class="card bg-transparent border-0 text-center p-5">
                                <h1 class="card-title item-title mt-3">Title</h1>
                                <div class="card-body text-contrast-900">
                                    <p class="card-text fw-normal item-subtitle">Subtitle</p>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent text-center">
                                <p><span class="item-authors">Authors</span></p><br>
                                <p><span class="item-year">August 28, 2024</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="flip-card-back h-100" style="background-image: url('{{ asset('images/fis2.png') }}'); background-size: 350px; background-repeat: no-repeat; background-position: center;">
                        <div class="flip-card-inner h-100 d-flex flex-column align-items-center justify-content-center">
                            <p class="mb-0"><h4 class="item-isbn text-light">1234567890</h4></p>
                            <a class="btn btn-outline-light mt-2 item-details-button">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- MODALS --}}
    @include('theme.layouts.modals')
    
    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="items" name="items">
        <input type="text" id="status" name="status">
    </form>

@endsection

@section('pagejs')
	
    <!-- jQuery -->
    <script src="{{ asset('theme/js/jquery-3.6.0.min.js') }}"></script>

    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('js/listing.js') }}"></script>
        
    <script>
        // document.getElementById('select-all').addEventListener('change', function() {
        //     var checkboxes = document.querySelectorAll('.select-item');
        //     checkboxes.forEach(function(checkbox) {
        //         checkbox.checked = this.checked;
        //     }, this);
        // });
        
        function single_delete(id){
            $('.single-delete').modal('show');
            $('.btn-delete').on('click', function() {
                post_form("{{ route('items.single-delete') }}",'',id);
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
                    post_form("{{ route('items.multiple-delete') }}", '', selected_items);
                });
            }
        }
        
        function single_restore(id){
            post_form("{{ route('items.single-restore') }}",'',id);
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
                    post_form("{{ route('items.multiple-restore') }}", '', selected_items);
                });
            }
        }
        
        function post_form(url,status,items){
            $('#posting_form').attr('action',url);
            $('#items').val(items);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        function show_item(id, title, subtitle, authors_arr, date, isbn, image_cover){
            
            
            const authors = JSON.parse(authors_arr);

            const author_names = authors.map(author => author.name).join('<br>');
            const year = new Date(date).getFullYear();

            if(!image_cover){
                image_cover= 'images/empty.png';
            }
            console.log(image_cover);
            $('.show-item').modal('show');
            $('.item-title').html(title);
            $('.item-subtitle').html(subtitle);
            $('.item-authors').html(author_names);
            $('.item-year').html(year);
            $('.item-isbn').html(isbn);
            $('#item_image').css("background-image","url('"+image_cover+"')");
            $('.item-details-button').attr('href', '{{ route('items.show', '') }}/' + id);

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
            jQuery('#items_tbl').dataTable();
        });
    </script> --}}
@endsection