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
                            <td>User</td>
                            <td colspan="3">Search</td>
                        </tr>
                        <tr>
                            <td><input type="date" class="form-control" name="start_date" id="start_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['start_date']}}" @endif></td>
                            <td><input type="date" class="form-control" name="end_date" id="end_date" style="font-size:12px;"  @if(isset($_GET['start_date'])) value="{{$_GET['end_date']}}" @endif></td>
                            <td>
                                <select name="user" id="user" class="form-control" style="font-size:12px;">
                                    <option value="">- All -</option>
                                    @php $users = \App\Models\User::orderBy('name')->get(); @endphp
                                    @forelse($users as $user)
                                        <option value="{{$user->id}}" @if(isset($_GET['user']) && $_GET['user']==$user->id) selected @endif>{{$user->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </td>
                            <td width="30%"><input name="search" type="search" id="search" class="form-control" placeholder="Search by Activity Description"  @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif style="font-size:12px;"></td>
                            <td>
                                <input type="submit" class="btn text-light" value="Search" style="font-size:12px; background-color: #3d80e3;">
                            </td>
                           
                        </tr>
                        <tr><td><a href="{{route('reports.audit-trail')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                    </table>
                </form>
            </div>


            <div class="table-responsive-faker">
                <table id="audit-report" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>
                            <th width="10%">User</th>
                            <th width="20%">Activity</th>
                            <th width="10%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rs as $r)
                            <tr id="row{{$r->id}}">
                                <td>{{ User::getName($r->log_by) }}</td>
                                <td>{{ $r->activity_desc }}</td>
                                <td>{{ $r->activity_date }}</td>
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
        $(document).ready(function() {

            var table = new DataTable('#audit-report', {
                order: [[2, 'desc']], 
                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: 'copy',
                                action: function(e, dt, button, config) {
                                    logExportActivity('copy');  // Custom audit log function
                                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                                }
                            },
                            {
                                extend: 'excel',
                                action: function(e, dt, button, config) {
                                    logExportActivity('excel');  // Custom audit log function
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                                }
                            },
                            {
                                extend: 'pdf',
                                action: function(e, dt, button, config) {
                                    logExportActivity('pdf');  // Custom audit log function
                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                                },
                                orientation: 'landscape', 
                                pageSize: 'A4',
                                title: '{{ $page->name }} | Foreign Service Institute',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csv',
                                action: function(e, dt, button, config) {
                                    logExportActivity('csv');  // Custom audit log function
                                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                                }
                            },
                            {
                                extend: 'print',
                                action: function(e, dt, button, config) {
                                    logExportActivity('print');  // Custom audit log function
                                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                                }
                            },
                            {
                                extend: 'colvis'
                            }
                        ]
                    }
                },

                columnDefs: [
                    {
                        visible: false,
                        target: []
                    }
                ]
            });
        });
    </script>
@endsection