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
                            <td>Start Date</td>
                            <td>End Date</td>
                            {{-- <td>Receiver</td> --}}
                            <td>Status</td>
                            <td colspan="3">Search</td>
                        </tr>
                        <tr>
                            <td><input type="date" class="form-control" name="start_date" id="start_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['start_date']}}" @endif></td>
                            <td><input type="date" class="form-control" name="end_date" id="end_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['end_date']}}" @endif></td>
                            {{-- <td>
                                <select name="receiver" id="receiver" class="form-control" style="font-size:12px;">
                                    <option value="">- All -</option>
                                    @php $receivers = \App\Models\Custom\Receiver::orderBy('name')->get(); @endphp
                                    @forelse($receivers as $receiver)
                                        <option value="{{$receiver->id}}" @if(isset($_GET['receiver']) && $_GET['receiver']==$receiver->id) selected @endif>{{$receiver->name}}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </td> --}}
                            <td>
                                <select name="status" id="status" class="form-control" style="font-size:12px;">
                                    <option value="" selected>- All -</option>
                                    <option value="SAVED" @if(isset($_GET['status']) && $_GET['status']=='SAVED') selected @endif>SAVED</option>
                                    <option value="POSTED" @if(isset($_GET['status']) && $_GET['status']=='POSTED') selected @endif>POSTED</option>
                                    <option value="CANCELLED" @if(isset($_GET['status']) && $_GET['status']=='CANCELLED') selected @endif>CANCELLED</option>
                                </select>
                            </td>
                            <td width="30%"><input name="search" type="search" id="search" class="form-control" placeholder="Search by Ref#, SKU, Title, Remarks"  @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif style="font-size:12px;"></td>
                            <td>
                                <input type="submit" class="btn text-light" value="Search" style="font-size:12px; background-color: #3d80e3;">
                            </td>
                           
                        </tr>
                        <tr><td><a href="{{route('reports.issuance')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                    </table>
                </form>
            </div>
            <div class="table-responsive-faker">
                <table id="report" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-primary">
                        <tr>
                            <th>Ref #</th>
                            <th>Technical Report #</th>
                            <th>Date Released</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            {{-- <th>Receiving Agency</th> --}}
                            <th>Truck Plate #</th>
                            <th>Receiver</th>
                            <th>Remarks</th>
                            <th>Posted By</th>
                            <th>Post Date</th>
                            <th>Item SKU</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Cost</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rs as $r)
                            <tr id="row{{$r->id}}">
                                <td>{{ $r->ref_no }}</td>
                                <td>{{ $r->technical_report_no ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($r->date_released)->format('Y-m-d') }}</td>
                                <td>{{ User::getName($r->created_by) }}</td>
                                <td>{{ Carbon\Carbon::parse($r->hcreated)->format('Y-m-d') }}</td>
                                <td>{{ $r->status }}</td>
                                {{-- <td>{!! \App\Models\Custom\IssuanceHeader::receivers_name($r->hid) !!}</td> --}}
                                <td>{{ $r->plate_no }}</td>
                                <td>{{ $r->actual_receiver }}</td>
                                <td>{{ $r->remarks }}</td>
                                <td>{{ User::getName($r->posted_by) }}</td>
                                <td>{{ Setting::date_for_listing($r->posted_at) }}</td>
                                <td>{{ $r->bsku }}</td>
                                <td>{{ $r->bname }}</td>
                                <td>{{ $r->quantity }}</td>
                                <td>{{ $r->price }}</td>
                                <td>{{ number_format(($r->quantity * $r->price),2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-danger p-5" colspan="100%">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
               

            </div>

        </div>

    </div>

@endsection

@section('pagejs')
     <script>
        var target_cols = [3,4,8,9,10,14,15];
    </script>
@endsection