@extends('theme.layouts.report')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-12 text-center">
                <h4 class="text-uppercase">{{ $page->name }}<br><small>{{ Setting::info()->company_name }}</small></h4>
            </div>
            
        </div>
        
        <div class="row mt-5">
            <div>
                <form class="d-flex align-items-center" id="searchForm" style="margin-bottom:10px;font-size: 12px !important;">
                    <input type="hidden" name="is_search" id="is_search" value="1">
                    <table width="100%" style="margin-bottom: 0px;">
                        <tr style="font-size:12px;font-weight:bold;">
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
                           
                        </tr>
                        <tr><td><a href="{{route('reports.items')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                    </table>
                </form>
            </div>


            <div class="table-responsive-faker">
                <table id="report" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-primary">
                        <tr>
                            <th>SKU</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Cost</th>
                            <th>Inventory</th>
                            <th>Minimum Stock</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rs as $r)
                            @php $item = \App\Models\Custom\Item::withTrashed()->find($r->bid); @endphp
                            
                            <tr id="row{{$r->bid}}">
                                <td>{{ $r->sku }}</td>
                                <td><strong>{{ $r->bname }}</strong></td>
                                <td>{{ $r->cname }}</td>
                                <td>{{ $r->unit }}</td>
                                <td>{{ number_format(($r->price),2) }}</td>
                                <td>{{ $item->Inventory }}</td>
                                <td>{{ $r->minimum_stock }}</td>
                                <td>{{ $r->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-danger p-5" colspan="100%">No item available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="row">
                    {{-- <div class="col-md-12">
                        {{ $rs->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div> --}}
                </div>

            </div>

        </div>

    </div>

@endsection

@section('pagejs')
     <script>
        var target_cols = [4,7];
    </script>
@endsection